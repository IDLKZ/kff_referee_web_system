<?php

namespace App\Livewire\Kff;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\MatchJudge;
use App\Models\MatchModel;
use App\Models\MatchOperationLog;
use App\Models\Operation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('kff.layout')]
#[Title('Head Referee Approve Detail')]
class HeadRefereeApproveDetail extends Component
{
    public int $matchId;

    // Judge action modal
    public bool $showJudgeModal = false;
    public ?int $actionJudgeId = null;
    public string $actionJudgeName = '';
    public string $actionType = ''; // 'approve' or 'reject'
    public string $finalComment = '';

    // Brigade action modal
    public bool $showBrigadeModal = false;
    public string $brigadeActionType = ''; // 'approve' or 'reject'
    public string $brigadeComment = '';

    public function mount(int $match): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            403
        );

        $this->matchId = MatchModel::findOrFail($match)->id;
    }

    // ── Helpers ──────────────────────────────────────────────

    protected function loadMatch(): MatchModel
    {
        return MatchModel::with([
            'tournament',
            'season',
            'ownerClub',
            'guestClub',
            'city',
            'stadium',
            'operation',
            'judge_requirements.judge_type',
            'match_judges.user',
            'match_judges.judge_type',
        ])->findOrFail($this->matchId);
    }

    protected function computeSlotInfo(MatchModel $match): array
    {
        $info = [];

        foreach ($match->judge_requirements as $req) {
            $typeId = $req->judge_type_id;
            $judges = $match->match_judges->where('type_id', $typeId);

            // Only judges who accepted (judge_response == 1) matter for HEAD decisions
            $acceptedJudges = $judges->filter(fn ($mj) => $mj->judge_response == 1);

            $approvedCount = $acceptedJudges->filter(fn ($mj) => $mj->final_status == 1)->count();
            $rejectedCount = $acceptedJudges->filter(fn ($mj) => $mj->final_status == -1)->count();
            $pendingCount = $acceptedJudges->filter(fn ($mj) => $mj->final_status == 0)->count();

            $info[$typeId] = [
                'requirement' => $req,
                'accepted' => $acceptedJudges->count(),
                'approved' => $approvedCount,
                'rejected' => $rejectedCount,
                'pending' => $pendingCount,
            ];
        }

        return $info;
    }

    /**
     * Determine brigade-level button availability and states.
     *
     * Rules:
     * - Buttons appear only when NO accepted judge has final_status == 0 (all decided)
     * - If any is_required judge has final_status == -1 → only reassignment available
     * - If all is_required approved AND all optional decided → both buttons available
     * - Approve brigade requires all is_required approved (final_status == 1)
     *   and all optional judges also approved (final_status == 1)
     */
    protected function computeBrigadeState(array $slotInfo): array
    {
        $allDecided = true;        // no pending judges remain
        $hasRequiredRejected = false; // any is_required judge rejected
        $allRequiredApproved = true;  // all is_required judges approved
        $allOptionalApproved = true;  // all optional judges approved (or none exist)
        $hasSlots = count($slotInfo) > 0;

        foreach ($slotInfo as $info) {
            if ($info['pending'] > 0) {
                $allDecided = false;
            }

            if ($info['requirement']->is_required) {
                if ($info['rejected'] > 0) {
                    $hasRequiredRejected = true;
                }
                if ($info['approved'] < $info['requirement']->qty) {
                    $allRequiredApproved = false;
                }
            } else {
                if ($info['rejected'] > 0) {
                    $allOptionalApproved = false;
                }
            }
        }

        // Buttons only show when all accepted judges have been decided (no pending)
        $showButtons = $hasSlots && $allDecided;

        // Can approve: all required approved AND all optional approved
        $canApprove = $showButtons && $allRequiredApproved && $allOptionalApproved && !$hasRequiredRejected;

        // Can reassign: buttons are shown (always available when buttons visible)
        $canReassign = $showButtons;

        return [
            'showButtons' => $showButtons,
            'canApprove' => $canApprove,
            'canReassign' => $canReassign,
            'hasRequiredRejected' => $hasRequiredRejected,
        ];
    }

    protected function transitionTo(string $operationValue): void
    {
        $match = MatchModel::findOrFail($this->matchId);
        $targetOperation = Operation::where('value', $operationValue)->firstOrFail();

        MatchOperationLog::create([
            'match_id' => $this->matchId,
            'from_operation_id' => $match->current_operation_id,
            'to_operation_id' => $targetOperation->id,
            'performed_by_id' => auth()->id(),
        ]);

        $match->update(['current_operation_id' => $targetOperation->id]);
    }

    protected function isAtApprovalStage(): bool
    {
        $match = MatchModel::findOrFail($this->matchId);
        $operation = Operation::find($match->current_operation_id);

        return $operation && $operation->value === OperationConstants::REFEREE_TEAM_APPROVAL;
    }

    // ── Judge Actions ────────────────────────────────────────

    public function openJudgeModal(int $matchJudgeId, string $type): void
    {
        $mj = MatchJudge::with('user')
            ->where('id', $matchJudgeId)
            ->where('match_id', $this->matchId)
            ->first();

        if (!$mj) {
            return;
        }

        $this->actionJudgeId = $matchJudgeId;
        $this->actionJudgeName = trim(($mj->user->last_name ?? '') . ' ' . ($mj->user->first_name ?? ''));
        $this->actionType = $type;
        $this->finalComment = '';
        $this->showJudgeModal = true;
    }

    public function closeJudgeModal(): void
    {
        $this->showJudgeModal = false;
        $this->actionJudgeId = null;
        $this->actionJudgeName = '';
        $this->actionType = '';
        $this->finalComment = '';
    }

    public function executeJudgeAction(): void
    {
        if (!$this->actionJudgeId || !$this->isAtApprovalStage()) {
            return;
        }

        if ($this->actionType === 'reject' && empty(trim($this->finalComment))) {
            session()->flash('toastr_error', __('crud.final_comment_placeholder'));
            return;
        }

        $mj = MatchJudge::where('id', $this->actionJudgeId)
            ->where('match_id', $this->matchId)
            ->first();

        if (!$mj || $mj->judge_response != 1) {
            return;
        }

        if ($this->actionType === 'approve') {
            $mj->update([
                'final_status' => 1,
                'final_comment' => $this->finalComment ?: null,
            ]);
            $this->closeJudgeModal();
            session()->flash('toastr_success', __('crud.judge_approved_success'));
        } elseif ($this->actionType === 'reject') {
            $mj->update([
                'final_status' => -1,
                'final_comment' => $this->finalComment,
            ]);
            $this->closeJudgeModal();
            session()->flash('toastr_success', __('crud.judge_rejected_success'));
        }
    }

    // ── Brigade Actions ──────────────────────────────────────

    public function openBrigadeModal(string $type): void
    {
        $this->brigadeActionType = $type;
        $this->brigadeComment = '';
        $this->showBrigadeModal = true;
    }

    public function closeBrigadeModal(): void
    {
        $this->showBrigadeModal = false;
        $this->brigadeActionType = '';
        $this->brigadeComment = '';
    }

    public function executeBrigadeAction(): void
    {
        if (!$this->isAtApprovalStage()) {
            return;
        }

        if ($this->brigadeActionType === 'approve') {
            $this->approveBrigade();
        } elseif ($this->brigadeActionType === 'reject') {
            $this->rejectBrigade();
        }

        $this->closeBrigadeModal();
    }

    protected function approveBrigade(): void
    {
        $match = $this->loadMatch();
        $slotInfo = $this->computeSlotInfo($match);
        $brigadeState = $this->computeBrigadeState($slotInfo);

        if (!$brigadeState['canApprove']) {
            session()->flash('toastr_error', __('crud.brigade_not_ready_error'));
            return;
        }

        $this->transitionTo(OperationConstants::SELECT_TRANSPORT_DEPARTURE);
        session()->flash('toastr_success', __('crud.brigade_approved_success'));
    }

    protected function rejectBrigade(): void
    {
        $this->transitionTo(OperationConstants::REFEREE_REASSIGNMENT);
        session()->flash('toastr_success', __('crud.brigade_rejected_success'));
    }

    // ── Render ──────────────────────────────────────────────

    public function render()
    {
        $match = $this->loadMatch();
        $operationValue = $match->operation->value ?? '';
        $slotInfo = $this->computeSlotInfo($match);
        $brigadeState = $this->computeBrigadeState($slotInfo);

        return view('livewire.kff.head-referee-approve-detail', [
            'match' => $match,
            'operationValue' => $operationValue,
            'slotInfo' => $slotInfo,
            'brigadeState' => $brigadeState,
        ]);
    }
}

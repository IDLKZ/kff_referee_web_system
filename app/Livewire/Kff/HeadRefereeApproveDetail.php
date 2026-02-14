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

            // Historical approved judges from previous rounds (is_actual = true)
            $historicalApproved = $judges->filter(fn ($mj) => $mj->is_actual === true)->count();

            // Current round: accepted judges (judge_response == 1, is_actual === null)
            $currentJudges = $judges->filter(fn ($mj) => $mj->judge_response == 1 && $mj->is_actual === null);

            $currentApproved = $currentJudges->filter(fn ($mj) => $mj->final_status == 1)->count();
            $currentRejected = $currentJudges->filter(fn ($mj) => $mj->final_status == -1)->count();
            $currentPending  = $currentJudges->filter(fn ($mj) => $mj->final_status == 0)->count();

            $info[$typeId] = [
                'requirement' => $req,
                'historical_approved' => $historicalApproved,
                'approved' => min($historicalApproved + $currentApproved, $req->qty),
                'rejected' => $currentRejected,   // current round only
                'pending' => $currentPending,      // current round only
            ];
        }
        return $info;
    }

    /**
     * Determine brigade-level button availability and states.
     *
     * Staffing = is_actual=true (historical) + is_actual=null & final_status=1 (current approved)
     *
     * Rules:
     * - Buttons appear only when NO current judge has final_status == 0 (all decided)
     * - If any required type not fully staffed (approved < qty) → only reassignment
     * - If all required staffed + some optional not staffed → both buttons
     * - If all types fully staffed → only approve trip
     */
    protected function computeBrigadeState(array $slotInfo): array
    {
        $allDecided = true;           // no pending current judges
        $allRequiredStaffed = true;   // all required types: approved >= qty
        $allFullyStaffed = true;      // all types (required + optional): approved >= qty
        $hasSlots = count($slotInfo) > 0;

        foreach ($slotInfo as $info) {
            if ($info['pending'] > 0) {
                $allDecided = false;
            }

            $staffed = $info['approved'] >= $info['requirement']->qty;

            if (!$staffed) {
                $allFullyStaffed = false;
            }

            if ($info['requirement']->is_required && !$staffed) {
                $allRequiredStaffed = false;
            }
        }

        // Buttons only show when all current judges have been decided (no pending)
        $showButtons = $hasSlots && $allDecided;

        // Can approve trip: all required types fully staffed
        $canApprove = $showButtons && $allRequiredStaffed;

        // Can reassign: not all types fully staffed (there are gaps to fill)
        $canReassign = $showButtons && !$allFullyStaffed;

        return [
            'showButtons' => $showButtons,
            'canApprove' => $canApprove,
            'canReassign' => $canReassign,
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

        if (!$mj || $mj->judge_response != 1 || $mj->is_actual !== null) {
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

        // Lock current-round judges: is_actual = true if approved, false if rejected
        MatchJudge::where('match_id', $this->matchId)
            ->where('judge_response', 1)
            ->whereNull('is_actual')
            ->get()
            ->each(fn ($mj) => $mj->update([
                'is_actual' => $mj->final_status == 1,
            ]));

        $this->transitionTo(OperationConstants::SELECT_TRANSPORT_DEPARTURE);
        session()->flash('toastr_success', __('crud.brigade_approved_success'));
    }

    protected function rejectBrigade(): void
    {
        // Lock current-round judges before reassignment
        MatchJudge::where('match_id', $this->matchId)
            ->where('judge_response', 1)
            ->whereNull('is_actual')
            ->get()
            ->each(fn ($mj) => $mj->update([
                'is_actual' => $mj->final_status == 1,
            ]));

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

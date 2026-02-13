<?php

namespace App\Livewire\Kff;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\JudgeRequirement;
use App\Models\MatchJudge;
use App\Models\MatchModel;
use App\Models\MatchOperationLog;
use App\Models\Operation;
use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('kff.layout')]
#[Title('Referee Approve Detail')]
class RefereeApproveDetail extends Component
{
    public int $matchId;

    // Invite modal
    public bool $showInviteModal = false;
    public ?int $inviteTypeId = null;
    public string $judgeSearch = '';
    public string $requestComment = '';

    // Remove confirmation
    public bool $showRemoveModal = false;
    public ?int $removingJudgeId = null;
    public string $removingJudgeName = '';

    // Confirm transition
    public bool $showConfirmTransition = false;
    public string $transitionTarget = '';

    public function mount(int $match): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
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

            // Active = pending response OR accepted and not rejected by head
            $activeCount = $judges->filter(fn ($mj) =>
                $mj->judge_response == 0 ||
                ($mj->judge_response == 1 && $mj->final_status >= 0)
            )->count();

            // Accepted = judge said yes and head hasn't rejected
            $acceptedCount = $judges->filter(fn ($mj) =>
                $mj->judge_response == 1 && $mj->final_status >= 0
            )->count();

            // Rejected by head (for reassignment display)
            $rejectedByHead = $judges->filter(fn ($mj) =>
                $mj->judge_response == 1 && $mj->final_status == -1
            )->count();

            $info[$typeId] = [
                'requirement' => $req,
                'active' => $activeCount,
                'accepted' => $acceptedCount,
                'available' => max(0, $req->qty - $activeCount),
                'rejectedByHead' => $rejectedByHead,
                'isMet' => $req->is_required
                    ? ($acceptedCount == $req->qty)
                    : true,
            ];
        }

        return $info;
    }

    protected function checkCanSubmit(array $slotInfo): bool
    {
        foreach ($slotInfo as $info) {
            if (!$info['isMet']) {
                return false;
            }
        }

        return count($slotInfo) > 0;
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

    // ── Actions ─────────────────────────────────────────────

    public function moveToAssignment(): void
    {
        $match = $this->loadMatch();

        if ($match->judge_requirements->isEmpty()) {
            session()->flash('toastr_error', __('crud.no_judge_requirements_error'));
            return;
        }

        $this->transitionTo(OperationConstants::REFEREE_ASSIGNMENT);
        session()->flash('toastr_success', __('crud.operation_changed_success'));
    }

    public function openInviteModal(int $typeId): void
    {
        $this->inviteTypeId = $typeId;
        $this->judgeSearch = '';
        $this->requestComment = '';
        $this->showInviteModal = true;
    }

    public function closeInviteModal(): void
    {
        $this->showInviteModal = false;
        $this->inviteTypeId = null;
        $this->judgeSearch = '';
        $this->requestComment = '';
    }

    public function inviteJudge(int $userId): void
    {
        $typeId = $this->inviteTypeId;

        // Validate type is in requirements
        $requirement = JudgeRequirement::where('match_id', $this->matchId)
            ->where('judge_type_id', $typeId)
            ->first();

        if (! $requirement) {
            session()->flash('toastr_error', __('crud.invalid_judge_type_error'));
            return;
        }

        // Check slots
        $match = $this->loadMatch();
        $slotInfo = $this->computeSlotInfo($match);

        if (! isset($slotInfo[$typeId]) || $slotInfo[$typeId]['available'] <= 0) {
            session()->flash('toastr_error', __('crud.slots_full_error'));
            return;
        }

        // Check uniqueness (non-trashed) - judge cannot be invited to the same match on any type
        $exists = MatchJudge::where('match_id', $this->matchId)
            ->where('judge_id', $userId)
            ->exists();

        if ($exists) {
            session()->flash('toastr_error', __('crud.judge_already_invited_error'));
            return;
        }

        // Verify user is SOCCER_REFEREE
        $user = User::with('role')->find($userId);
        if (! $user || $user->role?->value !== RoleConstants::SOCCER_REFEREE) {
            session()->flash('toastr_error', __('crud.invalid_judge_error'));
            return;
        }

        MatchJudge::create([
            'match_id' => $this->matchId,
            'type_id' => $typeId,
            'judge_id' => $userId,
            'request_comment' => $this->requestComment ?: null,
            'judge_response' => 0,
            'final_status' => 0,
            'created_by_id' => auth()->id(),
        ]);

        $this->closeInviteModal();
        session()->flash('toastr_success', __('crud.judge_invited_success'));
    }

    public function confirmRemoveJudge(int $matchJudgeId): void
    {
        $mj = MatchJudge::with('user')
            ->where('id', $matchJudgeId)
            ->where('match_id', $this->matchId)
            ->first();

        if ($mj) {
            $this->removingJudgeId = $matchJudgeId;
            $this->removingJudgeName = trim(($mj->user->last_name ?? '') . ' ' . ($mj->user->first_name ?? ''));
            $this->showRemoveModal = true;
        }
    }

    public function removeJudge(): void
    {
        if (! $this->removingJudgeId) {
            return;
        }

        $mj = MatchJudge::where('id', $this->removingJudgeId)
            ->where('match_id', $this->matchId)
            ->first();

        if ($mj) {
            $mj->delete();
        }

        $this->showRemoveModal = false;
        $this->removingJudgeId = null;
        $this->removingJudgeName = '';

        session()->flash('toastr_success', __('crud.judge_removed_success'));
    }

    public function confirmSubmitForReview(): void
    {
        $this->showConfirmTransition = true;
        $this->transitionTarget = 'review';
    }

    public function confirmMoveToAssignment(): void
    {
        $this->showConfirmTransition = true;
        $this->transitionTarget = 'assignment';
    }

    public function executeTransition(): void
    {
        $this->showConfirmTransition = false;

        if ($this->transitionTarget === 'assignment') {
            $this->moveToAssignment();
        } elseif ($this->transitionTarget === 'review') {
            $this->submitForReview();
        }

        $this->transitionTarget = '';
    }

    public function cancelTransition(): void
    {
        $this->showConfirmTransition = false;
        $this->transitionTarget = '';
    }

    protected function submitForReview(): void
    {
        $match = $this->loadMatch();
        $slotInfo = $this->computeSlotInfo($match);

        if (! $this->checkCanSubmit($slotInfo)) {
            session()->flash('toastr_error', __('crud.requirements_not_met_error'));
            return;
        }

        $this->transitionTo(OperationConstants::REFEREE_TEAM_APPROVAL);
        session()->flash('toastr_success', __('crud.submitted_for_review_success'));
    }

    // ── Render ──────────────────────────────────────────────

    public function render()
    {
        $match = $this->loadMatch();
        $operationValue = $match->operation->value ?? '';
        $slotInfo = $this->computeSlotInfo($match);
        $canSubmitForReview = $this->checkCanSubmit($slotInfo);

        // Search results for invite modal
        $searchResults = collect();

        if ($this->showInviteModal && strlen($this->judgeSearch) >= 2 && $this->inviteTypeId) {
            $refereeRole = Role::where('value', RoleConstants::SOCCER_REFEREE)->first();

            if ($refereeRole) {
                $isReassignment = $operationValue === OperationConstants::REFEREE_REASSIGNMENT;

                // Exclude all judges already assigned to this match (any type)
                $excludeQuery = MatchJudge::where('match_id', $this->matchId);

                if ($isReassignment) {
                    $excludeQuery->withTrashed();
                }

                $excludedIds = $excludeQuery->pluck('judge_id')->toArray();
                $search = $this->judgeSearch;

                $searchResults = User::where('role_id', $refereeRole->id)
                    ->where('is_active', true)
                    ->whereNotIn('id', $excludedIds)
                    ->where(function ($q) use ($search) {
                        $q->where('last_name', 'like', "%{$search}%")
                          ->orWhere('first_name', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->limit(20)
                    ->get();
            }
        }

        return view('livewire.kff.referee-approve-detail', [
            'match' => $match,
            'operationValue' => $operationValue,
            'slotInfo' => $slotInfo,
            'canSubmitForReview' => $canSubmitForReview,
            'searchResults' => $searchResults,
        ]);
    }
}

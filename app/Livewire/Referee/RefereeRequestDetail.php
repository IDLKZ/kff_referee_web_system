<?php

namespace App\Livewire\Referee;

use App\Constants\RoleConstants;
use App\Models\MatchJudge;
use App\Models\MatchModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('referee.layout')]
#[Title('Referee Request Detail')]
class RefereeRequestDetail extends Component
{
    public int $matchId;
    public bool $showResponseModal = false;
    public string $responseAction = ''; // 'accept' or 'decline'
    public string $responseComment = '';

    public function mount(int $match): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::SOCCER_REFEREE,
            403
        );

        $this->matchId = MatchModel::findOrFail($match)->id;
    }

    protected function loadMatch(): MatchModel
    {
        $userId = auth()->id();

        return MatchModel::with([
            'tournament',
            'season',
            'ownerClub',
            'guestClub',
            'city',
            'stadium',
            'operation',
            'judge_requirements.judge_type',
            'match_judges' => function ($query) use ($userId) {
                $query->where('judge_id', $userId);
            },
            'match_judges.judge_type',
            'match_judges.user',
        ])->findOrFail($this->matchId);
    }

    // Open modal for accept/decline
    public function openResponseModal(string $action): void
    {
        $this->responseAction = $action;
        $this->responseComment = '';
        $this->showResponseModal = true;
    }

    public function closeResponseModal(): void
    {
        $this->showResponseModal = false;
        $this->responseAction = '';
        $this->responseComment = '';
    }

    public function submitResponse(): void
    {
        $userId = auth()->id();

        $matchJudge = MatchJudge::where('match_id', $this->matchId)
            ->where('judge_id', $userId)
            ->first();

        if (! $matchJudge) {
            session()->flash('toastr_error', __('crud.invitation_not_found_error'));
            $this->closeResponseModal();
            return;
        }

        if ($this->responseAction === 'accept') {
            $matchJudge->update([
                'judge_response' => 1,
                'judge_comment' => $this->responseComment ?: null,
            ]);
            session()->flash('toastr_success', __('crud.invitation_accepted_success'));
        } else {
            $matchJudge->update([
                'judge_response' => -1,
                'judge_comment' => $this->responseComment ?: null,
            ]);
            session()->flash('toastr_success', __('crud.invitation_declined_success'));
        }

        $this->closeResponseModal();
    }

    public function render()
    {
        $match = $this->loadMatch();
        $locale = app()->getLocale();

        // Get my judge assignment for this match
        $myJudgeAssignments = $match->match_judges;

        return view('livewire.referee.referee-request-detail', [
            'match' => $match,
            'locale' => $locale,
            'myJudgeAssignments' => $myJudgeAssignments,
        ]);
    }
}

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

    // Actions for responding to invitation
    public function acceptInvitation(): void
    {
        $userId = auth()->id();

        $matchJudge = MatchJudge::where('match_id', $this->matchId)
            ->where('judge_id', $userId)
            ->first();

        if (! $matchJudge) {
            session()->flash('toastr_error', __('crud.invitation_not_found_error'));
            return;
        }

        $matchJudge->update([
            'judge_response' => 1,
            'judge_comment' => null,
        ]);

        session()->flash('toastr_success', __('crud.invitation_accepted_success'));
    }

    public function declineInvitation(): void
    {
        $userId = auth()->id();

        $matchJudge = MatchJudge::where('match_id', $this->matchId)
            ->where('judge_id', $userId)
            ->first();

        if (! $matchJudge) {
            session()->flash('toastr_error', __('crud.invitation_not_found_error'));
            return;
        }

        $matchJudge->update([
            'judge_response' => -1,
        ]);

        session()->flash('toastr_success', __('crud.invitation_declined_success'));
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

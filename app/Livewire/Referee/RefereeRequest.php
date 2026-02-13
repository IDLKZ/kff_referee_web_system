<?php

namespace App\Livewire\Referee;

use App\Constants\RoleConstants;
use App\Models\Club;
use App\Models\MatchJudge;
use App\Models\MatchModel;
use App\Models\Season;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('referee.layout')]
#[Title('Referee Requests')]
class RefereeRequest extends Component
{
    use WithPagination;

    public string $activeTab = 'waiting';

    // Filters
    public string $search = '';
    public ?int $filter_tournament_id = null;
    public ?int $filter_season_id = null;
    public ?int $filter_club_id = null;

    // Filter modal
    public bool $showFilterModal = false;

    protected array $waitingResponseQuery = [
        'judge_response' => 0,
    ];

    protected array $acceptedQuery = [
        'judge_response' => 1,
        'final_status' => 1,
    ];

    protected array $declinedQuery = [
        'judge_response' => -1,
        'final_status' => -1,
    ];

    public function mount(): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::SOCCER_REFEREE,
            403
        );
    }

    public function updatedSearch(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('acceptedPage');
        $this->resetPage('declinedPage');
    }

    public function updatedFilterTournamentId(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('acceptedPage');
        $this->resetPage('declinedPage');
    }

    public function updatedFilterSeasonId(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('acceptedPage');
        $this->resetPage('declinedPage');
    }

    public function updatedFilterClubId(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('acceptedPage');
        $this->resetPage('declinedPage');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function toggleFilterModal(): void
    {
        $this->showFilterModal = !$this->showFilterModal;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filter_tournament_id = null;
        $this->filter_season_id = null;
        $this->filter_club_id = null;
        $this->resetPage('waitingPage');
        $this->resetPage('acceptedPage');
        $this->resetPage('declinedPage');
        $this->showFilterModal = false;
    }

    public function applyFilters(): void
    {
        $this->showFilterModal = false;
    }

    protected function baseQuery()
    {
        $userId = auth()->id();

        return MatchModel::query()
            ->whereHas('match_judges', function ($query) use ($userId) {
                $query->where('judge_id', $userId);
            })
            ->with([
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
            ]);
    }

    protected function applyFilters($query)
    {
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('ownerClub', function ($q) use ($search) {
                    $q->where('short_name_ru', 'like', "%{$search}%")
                      ->orWhere('short_name_kk', 'like', "%{$search}%")
                      ->orWhere('short_name_en', 'like', "%{$search}%");
                })->orWhereHas('guestClub', function ($q) use ($search) {
                    $q->where('short_name_ru', 'like', "%{$search}%")
                      ->orWhere('short_name_kk', 'like', "%{$search}%")
                      ->orWhere('short_name_en', 'like', "%{$search}%");
                });
            });
        }

        if ($this->filter_tournament_id) {
            $query->where('tournament_id', $this->filter_tournament_id);
        }

        if ($this->filter_season_id) {
            $query->where('season_id', $this->filter_season_id);
        }

        if ($this->filter_club_id) {
            $query->where(function ($q) use ($this->filter_club_id) {
                $q->where('owner_club_id', $this->filter_club_id)
                  ->orWhere('guest_club_id', $this->filter_club_id);
            });
        }

        return $query->orderBy('start_at', 'desc');
    }

    protected function tabQuery($tab): mixed
    {
        $userId = auth()->id();

        return match ($tab) {
            'waiting' => $this->applyFilters(
                MatchModel::query()
                    ->whereHas('match_judges', function ($query) use ($userId) {
                        $query->where('judge_id', $userId)
                              ->where($this->waitingResponseQuery);
                    })
                    ->with([
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
                    ])
            ),
            'accepted' => $this->applyFilters(
                MatchModel::query()
                    ->whereHas('match_judges', function ($query) use ($userId) {
                        $query->where('judge_id', $userId)
                              ->where($this->acceptedQuery);
                    })
                    ->with([
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
                    ])
            ),
            'declined' => $this->applyFilters(
                MatchModel::query()
                    ->whereHas('match_judges', function ($query) use ($userId) {
                        $query->where('judge_id', $userId)
                              ->where(function ($q) {
                                    $q->where('judge_response', -1)
                                      ->orWhere('final_status', -1);
                                });
                    })
                    ->with([
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
                    ])
            ),
        };
    }

    protected function tabCount($tab): int
    {
        $userId = auth()->id();

        return match ($tab) {
            'waiting' => MatchModel::query()
                ->whereHas('match_judges', function ($query) use ($userId) {
                    $query->where('judge_id', $userId)
                          ->where($this->waitingResponseQuery);
                })
                ->count(),
            'accepted' => MatchModel::query()
                ->whereHas('match_judges', function ($query) use ($userId) {
                    $query->where('judge_id', $userId)
                          ->where($this->acceptedQuery);
                })
                ->count(),
            'declined' => MatchModel::query()
                ->whereHas('match_judges', function ($query) use ($userId) {
                    $query->where('judge_id', $userId)
                          ->where(function ($q) {
                                $q->where('judge_response', -1)
                                  ->orWhere('final_status', -1);
                            });
                })
                ->count(),
        };
    }

    public function render()
    {
        $waitingCount = $this->tabCount('waiting');
        $acceptedCount = $this->tabCount('accepted');
        $declinedCount = $this->tabCount('declined');

        $matches = match ($this->activeTab) {
            'accepted' => $this->tabQuery('accepted')->paginate(30, ['*'], 'acceptedPage'),
            'declined' => $this->tabQuery('declined')->paginate(30, ['*'], 'declinedPage'),
            default => $this->tabQuery('waiting')->paginate(30, ['*'], 'waitingPage'),
        };

        return view('livewire.referee.referee-request', [
            'matches' => $matches,
            'waitingCount' => $waitingCount,
            'acceptedCount' => $acceptedCount,
            'declinedCount' => $declinedCount,
            'tournaments' => Tournament::where('is_active', true)->orderBy('title_ru')->get(),
            'seasons' => Season::orderBy('title_ru')->get(),
            'clubs' => Club::orderBy('short_name_ru')->get(),
        ]);
    }
}

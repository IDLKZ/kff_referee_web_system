<?php

namespace App\Livewire\Kff;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\City;
use App\Models\MatchLogist;
use App\Models\MatchModel;
use App\Models\Operation;
use App\Models\Season;
use App\Models\Tournament;
use App\Models\TransportType;
use App\Models\Trip;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('kff.layout')]
#[Title('KFF Trips')]
class KffTrips extends Component
{
    use WithPagination;

    public string $activeTab = 'awaiting';

    // Filters
    public string $search = '';
    public ?int $filter_tournament_id = null;
    public ?int $filter_season_id = null;

    // Filter modal
    public bool $showFilterModal = false;

    // Match detail modal
    public bool $showMatchDetailModal = false;
    public ?int $selectedMatchId = null;
    public ?MatchModel $selectedMatch = null;

    // Trip modal for logistician
    public bool $showTripModal = false;
    public ?int $tripMatchId = null;
    public ?int $tripJudgeId = null;
    public ?int $trip_transport_type_id = null;
    public ?int $trip_departure_city_id = null;
    public ?string $trip_logist_info = null;
    public ?string $trip_logist_comment = null;

    public function mount(): void
    {
        // Accessible for all KFF_PFLK_GROUP roles
        abort_unless(
            auth()->user()->role->group === RoleConstants::KFF_PFLK_GROUP,
            403
        );
    }

    public function updatedSearch(): void
    {
        $this->resetPage('awaitingPage');
        $this->resetPage('myTripsPage');
    }

    public function updatedFilterTournamentId(): void
    {
        $this->resetPage('awaitingPage');
        $this->resetPage('myTripsPage');
    }

    public function updatedFilterSeasonId(): void
    {
        $this->resetPage('awaitingPage');
        $this->resetPage('myTripsPage');
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
        $this->resetPage('awaitingPage');
        $this->resetPage('myTripsPage');
        $this->showFilterModal = false;
    }

    public function applyFilters(): void
    {
        $this->showFilterModal = false;
    }

    public function openMatchDetailModal(int $matchId): void
    {
        $this->selectedMatchId = $matchId;
        $this->selectedMatch = MatchModel::with([
            'tournament',
            'season',
            'ownerClub',
            'guestClub',
            'city',
            'stadium',
            'operation',
            'judge_requirements.judge_type',
            'match_judges.judge_type',
            'match_judges.user',
            'match_judges.user.role',
        ])->findOrFail($matchId);
        $this->showMatchDetailModal = true;
    }

    public function closeMatchDetailModal(): void
    {
        $this->showMatchDetailModal = false;
        $this->selectedMatchId = null;
        $this->selectedMatch = null;
    }

    public function openTripModalForJudge(int $matchId, int $judgeId): void
    {
        $this->tripMatchId = $matchId;
        $this->tripJudgeId = $judgeId;

        $existing = Trip::where('match_id', $matchId)
            ->where('judge_id', $judgeId)
            ->first();

        if ($existing) {
            $this->trip_transport_type_id = $existing->transport_type_id;
            $this->trip_departure_city_id = $existing->departure_city_id;
            $this->trip_logist_info = $existing->info;
            $this->trip_logist_comment = null;
        } else {
            $this->trip_transport_type_id = null;
            $this->trip_departure_city_id = null;
            $this->trip_logist_info = null;
            $this->trip_logist_comment = null;
        }

        $this->showTripModal = true;
    }

    public function closeTripModal(): void
    {
        $this->showTripModal = false;
        $this->tripMatchId = null;
        $this->tripJudgeId = null;
        $this->trip_transport_type_id = null;
        $this->trip_departure_city_id = null;
        $this->trip_logist_info = null;
        $this->trip_logist_comment = null;
    }

    public function saveTripForJudge(): void
    {
        $this->validate([
            'trip_transport_type_id' => 'required|exists:transport_types,id',
            'trip_departure_city_id' => 'required|exists:cities,id',
            'trip_logist_info' => 'nullable|string|max:1000',
        ]);

        $match = MatchModel::findOrFail($this->tripMatchId);

        // Get operation ID for SELECT_TRANSPORT_DEPARTURE
        $operationId = Operation::where('value', OperationConstants::SELECT_TRANSPORT_DEPARTURE)
            ->pluck('id')
            ->first();

        Trip::updateOrCreate(
            [
                'match_id' => $this->tripMatchId,
                'judge_id' => $this->tripJudgeId,
            ],
            [
                'operation_id' => $operationId,
                'transport_type_id' => $this->trip_transport_type_id,
                'departure_city_id' => $this->trip_departure_city_id,
                'arrival_city_id' => $match->city_id,
                'logist_id' => auth()->id(),
                'info' => $this->trip_logist_info,
            ]
        );

        $this->closeTripModal();

        session()->flash('message', __('crud.trip_saved_success'));
    }

    protected function applyQueryFilters($query)
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

        return $query->orderBy('start_at', 'desc');
    }

    protected function awaitingQuery()
    {
        // Get judge IDs where they have accepted invitations
        $judgeIds = \App\Models\MatchJudge::where('final_status', 1)
            ->where('judge_response', 1)
            ->where('is_actual', true)
            ->pluck('judge_id')
            ->unique()
            ->values();

        // Get match IDs for these judges
        $matchIds = \App\Models\MatchJudge::whereIn('judge_id', $judgeIds)
            ->pluck('match_id')
            ->unique()
            ->values();

        // Get operation IDs for SELECT_TRANSPORT_DEPARTURE and TRIP_PROCESSING
        $operationIds = Operation::whereIn('value', [
                OperationConstants::SELECT_TRANSPORT_DEPARTURE,
                OperationConstants::TRIP_PROCESSING,
            ])
            ->pluck('id');

        return $this->applyQueryFilters(
            MatchModel::query()
                ->whereIn('id', $matchIds)
                ->whereIn('current_operation_id', $operationIds)
                ->with([
                    'tournament',
                    'season',
                    'ownerClub',
                    'guestClub',
                    'city',
                    'stadium',
                    'operation',
                    'judge_requirements.judge_type',
                    'match_judges.judge_type',
                    'match_judges.user',
                    'match_judges.user.role',
                    'trips' => function ($q) use ($judgeIds) {
                        $q->whereIn('judge_id', $judgeIds);
                    },
                    'trips.transport_type',
                    'trips.city',
                    'trips.arrival_city',
                ])
        );
    }

    protected function myTripsQuery()
    {
        $logistId = auth()->id();

        // Get judge IDs where they have accepted invitations
        $judgeIds = \App\Models\MatchJudge::where('final_status', 1)
            ->where('judge_response', 1)
            ->where('is_actual', true)
            ->pluck('judge_id')
            ->unique()
            ->values();

        // Get match IDs for these judges
        $matchIds = \App\Models\MatchJudge::whereIn('judge_id', $judgeIds)
            ->pluck('match_id')
            ->unique()
            ->values();

        // Get operation IDs for SELECT_TRANSPORT_DEPARTURE and TRIP_PROCESSING
        $operationIds = Operation::whereIn('value', [
                OperationConstants::SELECT_TRANSPORT_DEPARTURE,
                OperationConstants::TRIP_PROCESSING,
            ])
            ->pluck('id');

        // Get trips created by this logist for these judges
        $query = Trip::whereIn("match_id", $matchIds)
            ->whereIn('judge_id', $judgeIds)
            ->with([
                'match.tournament',
                'match.season',
                'match.ownerClub',
                'match.guestClub',
                'match.city',
                'match.stadium',
                'match.operation',
                'operation',
                'judge',
                'transport_type',
                'city',
                'arrival_city',
            ]);

        if ($this->search) {
            $search = $this->search;
            $query->whereHas('match', function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->whereHas('ownerClub', function ($q3) use ($search) {
                        $q3->where('short_name_ru', 'like', "%{$search}%")
                           ->orWhere('short_name_kk', 'like', "%{$search}%")
                           ->orWhere('short_name_en', 'like', "%{$search}%");
                    })->orWhereHas('guestClub', function ($q3) use ($search) {
                        $q3->where('short_name_ru', 'like', "%{$search}%")
                           ->orWhere('short_name_kk', 'like', "%{$search}%")
                           ->orWhere('short_name_en', 'like', "%{$search}%");
                    });
                });
            });
        }

        if ($this->filter_tournament_id) {
            $query->whereHas('match', function ($q) {
                $q->where('tournament_id', $this->filter_tournament_id);
            });
        }

        if ($this->filter_season_id) {
            $query->whereHas('match', function ($q) {
                $q->where('season_id', $this->filter_season_id);
            });
        }

        return $query->orderByDesc('created_at');
    }

    protected function awaitingCount(): int
    {
        // Get judge IDs where they have accepted invitations
        $judgeIds = \App\Models\MatchJudge::where('final_status', 1)
            ->where('judge_response', 1)
            ->where('is_actual', true)
            ->pluck('judge_id')
            ->unique()
            ->values();

        // Get match IDs for these judges
        $matchIds = \App\Models\MatchJudge::whereIn('judge_id', $judgeIds)
            ->pluck('match_id')
            ->unique()
            ->values();

        // Get operation IDs for SELECT_TRANSPORT_DEPARTURE and TRIP_PROCESSING
        $operationIds = Operation::whereIn('value', [
                OperationConstants::SELECT_TRANSPORT_DEPARTURE,
                OperationConstants::TRIP_PROCESSING,
            ])
            ->pluck('id');

        return MatchModel::query()
            ->whereIn('id', $matchIds)
            ->whereIn('current_operation_id', $operationIds)
            ->count();
    }

    protected function myTripsCount(): int
    {
        $logistId = auth()->id();

        // Get judge IDs where they have accepted invitations
        $judgeIds = \App\Models\MatchJudge::where('final_status', 1)
            ->where('judge_response', 1)
            ->where('is_actual', true)
            ->pluck('judge_id')
            ->unique()
            ->values();

        return Trip::where('logist_id', $logistId)
            ->whereIn('judge_id', $judgeIds)
            ->count();
    }

    public function render()
    {
        $awaitingCount = $this->awaitingCount();
        $myTripsCount = $this->myTripsCount();

        if ($this->activeTab === 'my_trips') {
            $trips = $this->myTripsQuery()->paginate(30, ['*'], 'myTripsPage');
            $matches = null;
        } else {
            $matches = $this->awaitingQuery()->paginate(30, ['*'], 'awaitingPage');
            $trips = null;
        }

        return view('livewire.kff.kff-trips', [
            'matches' => $matches,
            'trips' => $trips,
            'awaitingCount' => $awaitingCount,
            'myTripsCount' => $myTripsCount,
            'tournaments' => Tournament::where('is_active', true)->orderBy('title_ru')->get(),
            'seasons' => Season::orderBy('title_ru')->get(),
            'transportTypes' => TransportType::where('is_active', true)->orderBy('title_ru')->get(),
            'cities' => City::orderBy('title_ru')->get(),
        ]);
    }
}

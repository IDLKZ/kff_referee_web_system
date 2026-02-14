<?php

namespace App\Livewire\Referee;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\City;
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

#[Layout('referee.layout')]
#[Title('Referee Trips')]
class RefereeTrips extends Component
{
    use WithPagination;

    public string $activeTab = 'awaiting';

    // Filters
    public string $search = '';
    public ?int $filter_tournament_id = null;
    public ?int $filter_season_id = null;

    // Filter modal
    public bool $showFilterModal = false;

    // Trip modal
    public bool $showTripModal = false;
    public ?int $tripMatchId = null;
    public ?int $tripId = null;
    public ?int $trip_transport_type_id = null;
    public ?int $trip_departure_city_id = null;
    public ?string $trip_judge_comment = null;

    public function mount(): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::SOCCER_REFEREE,
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

    public function openTripModal(int $matchId): void
    {
        $this->tripMatchId = $matchId;
        $userId = auth()->id();

        $existing = Trip::where('match_id', $matchId)
            ->where('judge_id', $userId)
            ->first();

        if ($existing) {
            $this->tripId = $existing->id;
            $this->trip_transport_type_id = $existing->transport_type_id;
            $this->trip_departure_city_id = $existing->departure_city_id;
            $this->trip_judge_comment = $existing->judge_comment;
        } else {
            $this->tripId = null;
            $this->trip_transport_type_id = null;
            $this->trip_departure_city_id = null;
            $this->trip_judge_comment = null;
        }

        $this->showTripModal = true;
    }

    public function closeTripModal(): void
    {
        $this->showTripModal = false;
        $this->tripMatchId = null;
        $this->tripId = null;
        $this->trip_transport_type_id = null;
        $this->trip_departure_city_id = null;
        $this->trip_judge_comment = null;
    }

    public function saveTrip(): void
    {
        $this->validate([
            'trip_transport_type_id' => 'required|exists:transport_types,id',
            'trip_departure_city_id' => 'required|exists:cities,id',
            'trip_judge_comment' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();
        $match = MatchModel::findOrFail($this->tripMatchId);

        // Get operation ID for SELECT_TRANSPORT_DEPARTURE
        $operationId = Operation::where('value', OperationConstants::SELECT_TRANSPORT_DEPARTURE)
            ->pluck('id')
            ->first();

        Trip::updateOrCreate(
            [
                'match_id' => $this->tripMatchId,
                'judge_id' => $userId,
            ],
            [
                'operation_id' => $operationId,
                'transport_type_id' => $this->trip_transport_type_id,
                'departure_city_id' => $this->trip_departure_city_id,
                'arrival_city_id' => $match->city_id,
                'judge_comment' => $this->trip_judge_comment,
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
        $userId = auth()->id();

        $operationIds = Operation::where('value', OperationConstants::SELECT_TRANSPORT_DEPARTURE)
            ->pluck('id');

        return $this->applyQueryFilters(
            MatchModel::query()
                ->whereHas('match_judges', function ($query) use ($userId) {
                    $query->where('judge_id', $userId)
                          ->where('final_status', 1)
                          ->where('judge_response', 1)
                          ->where('is_actual', true);
                })
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
                    'trips' => function ($q) use ($userId) {
                        $q->where('judge_id', $userId);
                    },
                    'trips.operation',
                    'trips.transport_type',
                    'trips.city',
                    'trips.arrival_city',
                ])
        );
    }

    protected function myTripsQuery()
    {
        $userId = auth()->id();

        $query = Trip::where('judge_id', $userId)
            ->with([
                'match.tournament',
                'match.season',
                'match.ownerClub',
                'match.guestClub',
                'match.city',
                'match.stadium',
                'match.operation',
                'operation',
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
        $userId = auth()->id();

        $operationIds = Operation::where('value', OperationConstants::SELECT_TRANSPORT_DEPARTURE)
            ->pluck('id');

        return MatchModel::query()
            ->whereHas('match_judges', function ($query) use ($userId) {
                $query->where('judge_id', $userId)
                      ->where('final_status', 1)
                      ->where('judge_response', 1)
                      ->where('is_actual', true);
            })
            ->whereIn('current_operation_id', $operationIds)
            ->count();
    }

    protected function myTripsCount(): int
    {
        return Trip::where('judge_id', auth()->id())->count();
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

        return view('livewire.referee.referee-trips', [
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

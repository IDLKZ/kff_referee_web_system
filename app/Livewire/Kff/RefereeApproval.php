<?php

namespace App\Livewire\Kff;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\MatchModel;
use App\Models\Operation;
use App\Models\Season;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('kff.layout')]
#[Title('Referee Approval')]
class RefereeApproval extends Component
{
    use WithPagination;

    public string $activeTab = 'waiting';

    // Filters
    public string $search = '';
    public ?int $filter_tournament_id = null;
    public ?int $filter_season_id = null;

    // Filter modal
    public bool $showFilterModal = false;

    protected array $waitingOperations = [
        OperationConstants::MATCH_CREATED_WAITING_REFEREES,
        OperationConstants::REFEREE_ASSIGNMENT,
    ];

    protected array $reviewOperations = [
        OperationConstants::REFEREE_TEAM_APPROVAL,
    ];

    protected array $reassignmentOperations = [
        OperationConstants::REFEREE_REASSIGNMENT,
    ];

    public function mount(): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            403
        );
    }

    public function updatedSearch(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('reviewPage');
        $this->resetPage('reassignmentPage');
    }

    public function updatedFilterTournamentId(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('reviewPage');
        $this->resetPage('reassignmentPage');
    }

    public function updatedFilterSeasonId(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('reviewPage');
        $this->resetPage('reassignmentPage');
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
        $this->resetPage('waitingPage');
        $this->resetPage('reviewPage');
        $this->resetPage('reassignmentPage');
        $this->showFilterModal = false;
    }

    public function applyFilters(): void
    {
        $this->showFilterModal = false;
    }

    protected function baseQuery()
    {
        $query = MatchModel::query()
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
                })->orWhereHas('city', function ($q) use ($search) {
                    $q->where('title_ru', 'like', "%{$search}%")
                      ->orWhere('title_kk', 'like', "%{$search}%")
                      ->orWhere('title_en', 'like', "%{$search}%");
                });
            });
        }

        if ($this->filter_tournament_id) {
            $query->where('tournament_id', $this->filter_tournament_id);
        }

        if ($this->filter_season_id) {
            $query->where('season_id', $this->filter_season_id);
        }

        return $query;
    }

    protected function getOperationIds(array $operationValues): array
    {
        return Operation::whereIn('value', $operationValues)->pluck('id')->toArray();
    }

    protected function tabQuery(array $operationValues)
    {
        $operationIds = $this->getOperationIds($operationValues);

        return $this->baseQuery()
            ->whereIn('current_operation_id', $operationIds)
            ->orderBy('updated_at', 'desc');
    }

    protected function tabCount(array $operationValues): int
    {
        $operationIds = $this->getOperationIds($operationValues);
        $query = MatchModel::query()->whereIn('current_operation_id', $operationIds);

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
                })->orWhereHas('city', function ($q) use ($search) {
                    $q->where('title_ru', 'like', "%{$search}%")
                      ->orWhere('title_kk', 'like', "%{$search}%")
                      ->orWhere('title_en', 'like', "%{$search}%");
                });
            });
        }

        if ($this->filter_tournament_id) {
            $query->where('tournament_id', $this->filter_tournament_id);
        }

        if ($this->filter_season_id) {
            $query->where('season_id', $this->filter_season_id);
        }

        return $query->count();
    }

    public function render()
    {
        $waitingCount = $this->tabCount($this->waitingOperations);
        $reviewCount = $this->tabCount($this->reviewOperations);
        $reassignmentCount = $this->tabCount($this->reassignmentOperations);

        $matches = match ($this->activeTab) {
            'review' => $this->tabQuery($this->reviewOperations)->paginate(30, ['*'], 'reviewPage'),
            'reassignment' => $this->tabQuery($this->reassignmentOperations)->paginate(30, ['*'], 'reassignmentPage'),
            default => $this->tabQuery($this->waitingOperations)->paginate(30, ['*'], 'waitingPage'),
        };

        return view('livewire.kff.referee-approval', [
            'matches' => $matches,
            'waitingCount' => $waitingCount,
            'reviewCount' => $reviewCount,
            'reassignmentCount' => $reassignmentCount,
            'tournaments' => Tournament::where('is_active', true)->orderBy('title_ru')->get(),
            'seasons' => Season::orderBy('title_ru')->get(),
        ]);
    }
}

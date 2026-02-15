<?php

namespace App\Livewire\Kff;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\MatchModel;
use App\Models\MatchReport;
use App\Models\Operation;
use App\Models\Season;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('kff.layout')]
#[Title('Match Protocol Report')]
class MatchProtocolReport extends Component
{
    use WithPagination;

    public string $activeTab = 'waiting';

    // Filters
    public string $search = '';
    public ?int $filter_tournament_id = null;
    public ?int $filter_season_id = null;
    public string $sort_field = 'created_at';
    public string $sort_direction = 'desc';

    // Filter modal
    public bool $showFilterModal = false;

    protected array $waitingOperations = [
        OperationConstants::PROTOCOL_REVIEW,
    ];

    protected array $reprocessingOperations = [
        OperationConstants::PROTOCOL_REPROCESSING,
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
        $this->resetPage('reprocessingPage');
        $this->resetPage('allPage');
    }

    public function updatedFilterTournamentId(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('reprocessingPage');
        $this->resetPage('allPage');
    }

    public function updatedFilterSeasonId(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('reprocessingPage');
        $this->resetPage('allPage');
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
        $this->sort_field = 'created_at';
        $this->sort_direction = 'desc';
        $this->resetPage('waitingPage');
        $this->resetPage('reprocessingPage');
        $this->resetPage('allPage');
        $this->showFilterModal = false;
    }

    public function applyFilters(): void
    {
        $this->resetPage('waitingPage');
        $this->resetPage('reprocessingPage');
        $this->resetPage('allPage');
        $this->showFilterModal = false;
    }

    public function sortBy(string $field): void
    {
        if ($this->sort_field === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_field = $field;
            $this->sort_direction = 'desc';
        }
        $this->resetPage('waitingPage');
        $this->resetPage('reprocessingPage');
        $this->resetPage('allPage');
    }

    protected function baseQuery()
    {
        $query = MatchReport::query()
            ->with([
                'match.tournament',
                'match.season',
                'match.ownerClub',
                'match.guestClub',
                'match.city',
                'match.stadium',
                'match.operation',
                'match.judge_requirements.judge_type',
                'match.match_judges.user',
                'match.match_judges.judge_type',
                'match_report_documents.file',
                'match_report_documents.match_protocol_requirement',
                'judge',
                'checked_by',
            ]);

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('match.ownerClub', function ($q) use ($search) {
                    $q->where('short_name_ru', 'like', "%{$search}%")
                      ->orWhere('short_name_kk', 'like', "%{$search}%")
                      ->orWhere('short_name_en', 'like', "%{$search}%")
                      ->orWhere('full_name_ru', 'like', "%{$search}%")
                      ->orWhere('full_name_kk', 'like', "%{$search}%")
                      ->orWhere('full_name_en', 'like', "%{$search}%");
                })->orWhereHas('match.guestClub', function ($q) use ($search) {
                    $q->where('short_name_ru', 'like', "%{$search}%")
                      ->orWhere('short_name_kk', 'like', "%{$search}%")
                      ->orWhere('short_name_en', 'like', "%{$search}%")
                      ->orWhere('full_name_ru', 'like', "%{$search}%")
                      ->orWhere('full_name_kk', 'like', "%{$search}%")
                      ->orWhere('full_name_en', 'like', "%{$search}%");
                })->orWhereHas('match.city', function ($q) use ($search) {
                    $q->where('title_ru', 'like', "%{$search}%")
                      ->orWhere('title_kk', 'like', "%{$search}%")
                      ->orWhere('title_en', 'like', "%{$search}%");
                })->orWhereHas('judge', function ($q) use ($search) {
                    $q->where('last_name', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('patronymic', 'like', "%{$search}%");
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
            ->whereHas('match', function ($q) use ($operationIds) {
                $q->whereIn('current_operation_id', $operationIds);
            })
            ->orderBy($this->sort_field, $this->sort_direction);
    }

    protected function allReportsQuery()
    {
        return $this->baseQuery()
            ->orderBy($this->sort_field, $this->sort_direction);
    }

    protected function tabCount(array $operationValues): int
    {
        $operationIds = $this->getOperationIds($operationValues);
        $query = MatchReport::query()->whereHas('match', function ($q) use ($operationIds) {
            $q->whereIn('current_operation_id', $operationIds);
        });

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('match.ownerClub', function ($q) use ($search) {
                    $q->where('short_name_ru', 'like', "%{$search}%")
                      ->orWhere('short_name_kk', 'like', "%{$search}%")
                      ->orWhere('short_name_en', 'like', "%{$search}%");
                })->orWhereHas('match.guestClub', function ($q) use ($search) {
                    $q->where('short_name_ru', 'like', "%{$search}%")
                      ->orWhere('short_name_kk', 'like', "%{$search}%")
                      ->orWhere('short_name_en', 'like', "%{$search}%");
                })->orWhereHas('match.city', function ($q) use ($search) {
                    $q->where('title_ru', 'like', "%{$search}%")
                      ->orWhere('title_kk', 'like', "%{$search}%")
                      ->orWhere('title_en', 'like', "%{$search}%");
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

        return $query->count();
    }

    protected function allReportsCount(): int
    {
        $query = MatchReport::query();

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('match.ownerClub', function ($q) use ($search) {
                    $q->where('short_name_ru', 'like', "%{$search}%")
                      ->orWhere('short_name_kk', 'like', "%{$search}%")
                      ->orWhere('short_name_en', 'like', "%{$search}%");
                })->orWhereHas('match.guestClub', function ($q) use ($search) {
                    $q->where('short_name_ru', 'like', "%{$search}%")
                      ->orWhere('short_name_kk', 'like', "%{$search}%")
                      ->orWhere('short_name_en', 'like', "%{$search}%");
                })->orWhereHas('match.city', function ($q) use ($search) {
                    $q->where('title_ru', 'like', "%{$search}%")
                      ->orWhere('title_kk', 'like', "%{$search}%")
                      ->orWhere('title_en', 'like', "%{$search}%");
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

        return $query->count();
    }

    public function render()
    {
        $waitingCount = $this->tabCount($this->waitingOperations);
        $reprocessingCount = $this->tabCount($this->reprocessingOperations);
        $allCount = $this->allReportsCount();

        $reports = match ($this->activeTab) {
            'reprocessing' => $this->tabQuery($this->reprocessingOperations)->paginate(30, ['*'], 'reprocessingPage'),
            'all' => $this->allReportsQuery()->paginate(30, ['*'], 'allPage'),
            default => $this->tabQuery($this->waitingOperations)->paginate(30, ['*'], 'waitingPage'),
        };

        return view('livewire.kff.match-protocol-report', [
            'reports' => $reports,
            'waitingCount' => $waitingCount,
            'reprocessingCount' => $reprocessingCount,
            'allCount' => $allCount,
            'tournaments' => Tournament::where('is_active', true)->orderBy('title_ru')->get(),
            'seasons' => Season::orderBy('title_ru')->get(),
        ]);
    }
}

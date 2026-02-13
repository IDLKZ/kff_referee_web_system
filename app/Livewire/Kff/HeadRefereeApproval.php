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
#[Title('Head Referee Approval')]
class HeadRefereeApproval extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filter_tournament_id = null;
    public ?int $filter_season_id = null;
    public bool $showFilterModal = false;

    public function mount(): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            403
        );
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterTournamentId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterSeasonId(): void
    {
        $this->resetPage();
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
        $this->resetPage();
        $this->showFilterModal = false;
    }

    public function applyFilters(): void
    {
        $this->showFilterModal = false;
    }

    public function render()
    {
        $operationIds = Operation::where('value', OperationConstants::REFEREE_TEAM_APPROVAL)
            ->pluck('id')
            ->toArray();

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
            ])
            ->whereIn('current_operation_id', $operationIds);

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

        $matches = $query->orderBy('updated_at', 'desc')->paginate(30);

        return view('livewire.kff.head-referee-approval', [
            'matches' => $matches,
            'tournaments' => Tournament::where('is_active', true)->orderBy('title_ru')->get(),
            'seasons' => Season::orderBy('title_ru')->get(),
        ]);
    }
}

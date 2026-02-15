<?php

namespace App\Livewire\Referee;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\Club;
use App\Models\MatchJudge;
use App\Models\MatchModel;
use App\Models\MatchProtocolRequirement;
use App\Models\MatchReport;
use App\Models\MatchReportDocument;
use App\Models\Operation;
use App\Models\Season;
use App\Models\Tournament;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('referee.layout')]
#[Title('Referee Protocol Management')]
class RefereeProtocolManagement extends Component
{
    use WithPagination;

    public string $activeTab = 'pending';

    // Filters
    public string $search = '';
    public ?int $filter_tournament_id = null;
    public ?int $filter_season_id = null;
    public ?int $filter_club_id = null;

    // Filter modal
    public bool $showFilterModal = false;

    public function mount(): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::SOCCER_REFEREE,
            403
        );
    }

    public function updatedSearch(): void
    {
        $this->resetPage('pendingPage');
        $this->resetPage('my_reportsPage');
    }

    public function updatedFilterTournamentId(): void
    {
        $this->resetPage('pendingPage');
        $this->resetPage('my_reportsPage');
    }

    public function updatedFilterSeasonId(): void
    {
        $this->resetPage('pendingPage');
        $this->resetPage('my_reportsPage');
    }

    public function updatedFilterClubId(): void
    {
        $this->resetPage('pendingPage');
        $this->resetPage('my_reportsPage');
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
        $this->resetPage('pendingPage');
        $this->resetPage('my_reportsPage');
        $this->showFilterModal = false;
    }

    public function applyFilters(): void
    {
        $this->showFilterModal = false;
    }

    protected function getWaitingForProtocolOperation(): ?Operation
    {
        return Operation::where('value', OperationConstants::WAITING_FOR_PROTOCOL)->first();
    }

    /**
     * Tab 1: Pending Protocol Requirements
     * Get matches where the judge is assigned and the match is waiting for protocol
     */
    protected function pendingProtocolRequirementsQuery()
    {
        $userId = auth()->id();
        $waitingOperation = $this->getWaitingForProtocolOperation();

        if (!$waitingOperation) {
            return MatchProtocolRequirement::whereRaw('1 = 0');
        }

        // Step 1: Get judge's MatchJudge records (final_status=1, judge_response=1, is_actual=true)
        $matchJudges = MatchJudge::where('judge_id', $userId)
            ->where('final_status', 1)
            ->where('judge_response', 1)
            ->where('is_actual', true)
            ->get(['match_id', 'type_id']);

        if ($matchJudges->isEmpty()) {
            return MatchProtocolRequirement::whereRaw('1 = 0');
        }

        $matchIds = $matchJudges->pluck('match_id')->unique();
        $typeIds = $matchJudges->pluck('type_id')->unique();

        // Step 2: Get matches with WAITING_FOR_PROTOCOL operation
        $actualMatchIds = MatchModel::whereIn('id', $matchIds)
            ->where('current_operation_id', $waitingOperation->id)
            ->pluck('id');

        if ($actualMatchIds->isEmpty()) {
            return MatchProtocolRequirement::whereRaw('1 = 0');
        }

        // Step 3: Build query for protocol requirements
        $query = MatchProtocolRequirement::whereIn('match_id', $actualMatchIds)
            ->whereIn('judge_type_id', $typeIds);

        // Apply filters
        if ($this->search) {
            $search = $this->search;
            $query->whereHas('match', function ($q) use ($search) {
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
            $query->whereHas('match', function ($q) {
                $q->where('season_id', $this->filter_season_id);
            });
        }

        if ($this->filter_club_id) {
            $clubId = $this->filter_club_id;
            $query->whereHas('match', function ($q) use ($clubId) {
                $q->where(function ($q) use ($clubId) {
                    $q->where('owner_club_id', $clubId)
                      ->orWhere('guest_club_id', $clubId);
                });
            });
        }

        return $query->with([
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
        ])->orderBy('created_at', 'desc');
    }

    protected function pendingCount(): int
    {
        $query = $this->pendingProtocolRequirementsQuery();
        return $query->count();
    }

    /**
     * Tab 2: My Reports
     * Get match reports submitted by the judge
     */
    protected function myReportsQuery()
    {
        $userId = auth()->id();

        $query = MatchReport::where('judge_id', $userId)
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
            ]);

        // Apply filters
        if ($this->search) {
            $search = $this->search;
            $query->whereHas('match', function ($q) use ($search) {
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
            $query->whereHas('match', function ($q) {
                $q->where('tournament_id', $this->filter_tournament_id);
            });
        }

        if ($this->filter_season_id) {
            $query->whereHas('match', function ($q) {
                $q->where('season_id', $this->filter_season_id);
            });
        }

        if ($this->filter_club_id) {
            $clubId = $this->filter_club_id;
            $query->whereHas('match', function ($q) use ($clubId) {
                $q->where(function ($q) use ($clubId) {
                    $q->where('owner_club_id', $clubId)
                      ->orWhere('guest_club_id', $clubId);
                });
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    protected function myReportsCount(): int
    {
        return MatchReport::where('judge_id', auth()->id())->count();
    }

    /**
     * Get documents count for a report
     */
    public function getDocumentsCount(int $matchReportId): int
    {
        return MatchReportDocument::where('match_report_id', $matchReportId)
            ->where('judge_id', auth()->id())
            ->count();
    }

    /**
     * Get documents for a report
     */
    public function getReportDocuments(int $matchReportId): \Illuminate\Database\Eloquent\Collection
    {
        return MatchReportDocument::where('match_report_id', $matchReportId)
            ->where('judge_id', auth()->id())
            ->with('file', 'match_protocol_requirement')
            ->get();
    }

    public function render()
    {
        $pendingCount = $this->pendingCount();
        $myReportsCount = $this->myReportsCount();

        $items = match ($this->activeTab) {
            'my_reports' => $this->myReportsQuery()->paginate(30, ['*'], 'my_reportsPage'),
            default => $this->pendingProtocolRequirementsQuery()->paginate(30, ['*'], 'pendingPage'),
        };

        return view('livewire.referee.referee-protocol-management', [
            'items' => $items,
            'pendingCount' => $pendingCount,
            'myReportsCount' => $myReportsCount,
            'tournaments' => Tournament::where('is_active', true)->orderBy('title_ru')->get(),
            'seasons' => Season::orderBy('title_ru')->get(),
            'clubs' => Club::orderBy('short_name_ru')->get(),
        ]);
    }
}

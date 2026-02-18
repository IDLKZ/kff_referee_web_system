<?php

namespace App\Livewire\Admin;

use App\Constants\CategoryOperationConstants;
use App\Constants\PermissionConstants;
use App\Constants\RoleConstants;
use App\Models\Club;
use App\Models\JudgeRequirement;
use App\Models\JudgeType;
use App\Models\MatchLogist;
use App\Models\MatchModel;
use App\Models\MatchProtocolRequirement;
use App\Models\Operation;
use App\Models\Season;
use App\Models\Stadium;
use App\Models\Tournament;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Matches')]
class MatchManagement extends Component
{
    use WithPagination;

    // Search & Filter
    public string $search = '';
    public ?int $filter_tournament_id = null;
    public ?int $filter_season_id = null;
    public ?bool $filter_is_active = null;
    public ?bool $filter_is_finished = null;
    public ?bool $filter_is_canceled = null;

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingId = null;
    public ?int $tournament_id = null;
    public ?int $season_id = null;
    public ?int $stadium_id = null;
    public ?int $city_id = null;
    public ?int $owner_club_id = null;
    public ?int $guest_club_id = null;
    public ?int $winner_id = null;
    public ?int $owner_point = null;
    public ?int $guest_point = null;
    public ?int $round = null;
    public ?string $start_at = '';
    public ?string $end_at = '';
    public bool $is_active = true;
    public bool $is_finished = false;
    public bool $is_canceled = false;
    public ?string $cancel_reason = '';
    public ?string $info = '';
    public ?int $current_operation_id = null;

    // Inline judge requirements
    public array $judgeRequirements = [];

    // Inline match logists
    public array $matchLogists = [];
    public ?int $newLogistId = null;

    // Inline protocol requirements
    public array $protocolRequirements = [];

    // Delete target
    public ?int $deletingId = null;
    public string $deletingInfo = '';
    public bool $deletingIsTrashed = false;

    protected function rules(): array
    {
        return [
            'tournament_id' => ['required', 'integer', 'exists:tournaments,id'],
            'season_id' => ['required', 'integer', 'exists:seasons,id'],
            'stadium_id' => ['required', 'integer', 'exists:stadiums,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'owner_club_id' => ['required', 'integer', 'exists:clubs,id', 'different:guest_club_id'],
            'guest_club_id' => ['required', 'integer', 'exists:clubs,id', 'different:owner_club_id'],
            'winner_id' => ['nullable', 'integer', 'exists:clubs,id'],
            'owner_point' => ['nullable', 'integer', 'min:0'],
            'guest_point' => ['nullable', 'integer', 'min:0'],
            'round' => ['nullable', 'integer', 'min:1'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'is_active' => ['boolean'],
            'is_finished' => ['boolean'],
            'is_canceled' => ['boolean'],
            'cancel_reason' => ['nullable', 'required_if:is_canceled,true', 'string'],
            'info' => ['nullable', 'string'],
            'current_operation_id' => ['nullable', 'integer', 'exists:operations,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'owner_club_id.different' => __('crud.clubs_must_differ'),
            'guest_club_id.different' => __('crud.clubs_must_differ'),
        ];
    }

    public function updatedStadiumId($value): void
    {
        if ($value) {
            $stadium = Stadium::find($value);
            if ($stadium && $stadium->city_id) {
                $this->city_id = $stadium->city_id;
            }
        } else {
            $this->city_id = null;
        }
    }

    public function updatedFilterTournamentId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterSeasonId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterIsActive(): void
    {
        $this->resetPage();
    }

    public function updatedFilterIsFinished(): void
    {
        $this->resetPage();
    }

    public function updatedFilterIsCanceled(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::MATCHES_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::MATCHES_UPDATE);
        $match = MatchModel::withTrashed()
            ->with(['judge_requirements', 'match_logists', 'match_protocol_requirements'])
            ->findOrFail($id);

        $this->editingId = $match->id;
        $this->tournament_id = $match->tournament_id;
        $this->season_id = $match->season_id;
        $this->stadium_id = $match->stadium_id;
        $this->city_id = $match->city_id;
        $this->owner_club_id = $match->owner_club_id;
        $this->guest_club_id = $match->guest_club_id;
        $this->winner_id = $match->winner_id;
        $this->owner_point = $match->owner_point;
        $this->guest_point = $match->guest_point;
        $this->round = $match->round;
        $this->start_at = $match->start_at ? $match->start_at->format('Y-m-d\TH:i') : '';
        $this->end_at = $match->end_at ? $match->end_at->format('Y-m-d\TH:i') : '';
        $this->is_active = $match->is_active;
        $this->is_finished = $match->is_finished;
        $this->is_canceled = $match->is_canceled;
        $this->cancel_reason = $match->cancel_reason ?? '';
        $this->info = $match->info ? json_encode($match->info, JSON_UNESCAPED_UNICODE) : '';
        $this->current_operation_id = $match->current_operation_id;

        // Load judge requirements
        $this->judgeRequirements = $match->judge_requirements->map(function ($req) {
            return [
                'id' => $req->id,
                'judge_type_id' => $req->judge_type_id,
                'qty' => $req->qty,
                'is_required' => $req->is_required,
            ];
        })->toArray();

        // Load match logists
        $this->matchLogists = $match->match_logists->pluck('logist_id')->toArray();

        // Load protocol requirements
        $this->protocolRequirements = $match->match_protocol_requirements->map(function ($req) {
            return [
                'id' => $req->id,
                'judge_type_id' => $req->judge_type_id,
                'title_ru' => $req->title_ru,
                'title_kk' => $req->title_kk,
                'title_en' => $req->title_en,
                'info_ru' => $req->info_ru,
                'info_kk' => $req->info_kk,
                'info_en' => $req->info_en,
                'is_required' => $req->is_required,
            ];
        })->toArray();

        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::MATCHES_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::MATCHES_CREATE);
        }

        // Validate winner_id is one of the clubs
        if ($this->winner_id && $this->winner_id != $this->owner_club_id && $this->winner_id != $this->guest_club_id) {
            $this->addError('winner_id', __('crud.select_winner'));
            return;
        }

        $this->validate();

        // For new matches, set the first operation in the workflow if not specified
        if (!$this->isEditing && !$this->current_operation_id) {
            $firstOperation = Operation::where('is_first', true)
                ->where('is_active', true)
                ->whereHas('category_operation', function ($q) {
                    $q->where('value', CategoryOperationConstants::REFEREE_ASSIGNMENT);
                })
                ->first();
            $this->current_operation_id = $firstOperation ? $firstOperation->id : null;
        }

        $data = [
            'tournament_id' => $this->tournament_id,
            'season_id' => $this->season_id,
            'stadium_id' => $this->stadium_id,
            'city_id' => $this->city_id,
            'owner_club_id' => $this->owner_club_id,
            'guest_club_id' => $this->guest_club_id,
            'winner_id' => $this->winner_id ?: null,
            'owner_point' => $this->owner_point,
            'guest_point' => $this->guest_point,
            'round' => $this->round,
            'start_at' => $this->start_at ?: null,
            'end_at' => $this->end_at ?: null,
            'is_active' => $this->is_active,
            'is_finished' => $this->is_finished,
            'is_canceled' => $this->is_canceled,
            'cancel_reason' => $this->is_canceled ? ($this->cancel_reason ?: null) : null,
            'info' => $this->info ? json_decode($this->info, true) : null,
            'current_operation_id' => $this->current_operation_id,
        ];

        if ($this->isEditing) {
            $match = MatchModel::findOrFail($this->editingId);
            $match->update($data);
        } else {
            $match = MatchModel::create($data);
        }

        // Sync judge requirements
        $this->syncJudgeRequirements($match);

        // Sync match logists
        $this->syncMatchLogists($match);

        // Sync protocol requirements
        $this->syncProtocolRequirements($match);

        toastr()->success($this->isEditing ? __('crud.updated_success') : __('crud.created_success'));

        $this->showFormModal = false;
        $this->resetForm();
    }

    private function syncJudgeRequirements(MatchModel $match): void
    {
        $existingIds = $match->judge_requirements()->pluck('id')->toArray();
        $keepIds = [];

        foreach ($this->judgeRequirements as $req) {
            if (!empty($req['id'])) {
                // Update existing
                JudgeRequirement::where('id', $req['id'])->update([
                    'judge_type_id' => $req['judge_type_id'],
                    'qty' => $req['qty'],
                    'is_required' => $req['is_required'],
                ]);
                $keepIds[] = $req['id'];
            } else {
                // Create new
                $match->judge_requirements()->create([
                    'judge_type_id' => $req['judge_type_id'],
                    'qty' => $req['qty'],
                    'is_required' => $req['is_required'],
                ]);
            }
        }

        // Delete removed
        $toDelete = array_diff($existingIds, $keepIds);
        if ($toDelete) {
            JudgeRequirement::whereIn('id', $toDelete)->delete();
        }
    }

    private function syncMatchLogists(MatchModel $match): void
    {
        // Delete all existing
        MatchLogist::where('match_id', $match->id)->delete();

        // Create new
        foreach ($this->matchLogists as $logistId) {
            MatchLogist::create([
                'match_id' => $match->id,
                'logist_id' => $logistId,
            ]);
        }
    }

    private function syncProtocolRequirements(MatchModel $match): void
    {
        $existingIds = $match->match_protocol_requirements()->pluck('id')->toArray();
        $keepIds = [];

        foreach ($this->protocolRequirements as $req) {
            if (!empty($req['id'])) {
                // Update existing
                MatchProtocolRequirement::where('id', $req['id'])->update([
                    'tournament_id' => $match->tournament_id,
                    'judge_type_id' => $req['judge_type_id'],
                    'title_ru' => $req['title_ru'],
                    'title_kk' => $req['title_kk'] ?? null,
                    'title_en' => $req['title_en'] ?? null,
                    'info_ru' => $req['info_ru'],
                    'info_kk' => $req['info_kk'] ?? null,
                    'info_en' => $req['info_en'] ?? null,
                    'is_required' => $req['is_required'],
                ]);
                $keepIds[] = $req['id'];
            } else {
                // Create new
                $newReq = $match->match_protocol_requirements()->create([
                    'tournament_id' => $match->tournament_id,
                    'judge_type_id' => $req['judge_type_id'],
                    'title_ru' => $req['title_ru'],
                    'title_kk' => $req['title_kk'] ?? null,
                    'title_en' => $req['title_en'] ?? null,
                    'info_ru' => $req['info_ru'],
                    'info_kk' => $req['info_kk'] ?? null,
                    'info_en' => $req['info_en'] ?? null,
                    'is_required' => $req['is_required'],
                    'extensions' => '[]',
                ]);
                $keepIds[] = $newReq->id;
            }
        }

        // Delete removed
        $toDelete = array_diff($existingIds, $keepIds);
        if ($toDelete) {
            MatchProtocolRequirement::whereIn('id', $toDelete)->delete();
        }
    }

    // Inline judge requirement management
    public function addJudgeRequirement(): void
    {
        $this->judgeRequirements[] = [
            'id' => null,
            'judge_type_id' => null,
            'qty' => 1,
            'is_required' => true,
        ];
    }

    public function removeJudgeRequirement(int $index): void
    {
        unset($this->judgeRequirements[$index]);
        $this->judgeRequirements = array_values($this->judgeRequirements);
    }

    // Inline match logist management
    public function addLogist(): void
    {
        if ($this->newLogistId && !in_array($this->newLogistId, $this->matchLogists)) {
            $this->matchLogists[] = $this->newLogistId;
        }
        $this->newLogistId = null;
    }

    public function removeLogist(int $index): void
    {
        unset($this->matchLogists[$index]);
        $this->matchLogists = array_values($this->matchLogists);
    }

    // Inline protocol requirement management
    public function addProtocolRequirement(): void
    {
        $this->protocolRequirements[] = [
            'id' => null,
            'judge_type_id' => null,
            'title_ru' => '',
            'title_kk' => '',
            'title_en' => '',
            'info_ru' => '',
            'info_kk' => '',
            'info_en' => '',
            'is_required' => true,
        ];
    }

    public function removeProtocolRequirement(int $index): void
    {
        unset($this->protocolRequirements[$index]);
        $this->protocolRequirements = array_values($this->protocolRequirements);
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::MATCHES_DELETE);
        $match = MatchModel::withTrashed()
            ->with(['tournament', 'ownerClub', 'guestClub'])
            ->findOrFail($id);

        $this->deletingId = $match->id;
        $this->deletingIsTrashed = $match->trashed();
        $this->deletingInfo = ($match->tournament->title_ru ?? '') . ': ' .
                              ($match->ownerClub->short_name_ru ?? '?') . ' vs ' .
                              ($match->guestClub->short_name_ru ?? '?');
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::MATCHES_DELETE);

        $match = MatchModel::withTrashed()->findOrFail($this->deletingId);

        if ($match->trashed()) {
            $match->forceDelete();
        } else {
            $match->delete();
        }

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleSearchModal(): void
    {
        $this->showSearchModal = !$this->showSearchModal;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filter_tournament_id = null;
        $this->filter_season_id = null;
        $this->filter_is_active = null;
        $this->filter_is_finished = null;
        $this->filter_is_canceled = null;
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->isEditing = false;
        $this->tournament_id = null;
        $this->season_id = null;
        $this->stadium_id = null;
        $this->city_id = null;
        $this->owner_club_id = null;
        $this->guest_club_id = null;
        $this->winner_id = null;
        $this->owner_point = null;
        $this->guest_point = null;
        $this->round = null;
        $this->start_at = '';
        $this->end_at = '';
        $this->is_active = true;
        $this->is_finished = false;
        $this->is_canceled = false;
        $this->cancel_reason = '';
        $this->info = '';
        $this->current_operation_id = null;
        $this->judgeRequirements = [];
        $this->matchLogists = [];
        $this->newLogistId = null;
        $this->protocolRequirements = [];
        $this->resetValidation();
    }

    private function checkPermission(string $permission): void
    {
        if (!auth()->user()->hasPermission($permission)) {
            abort(403);
        }
    }

    public function getTournamentOptions()
    {
        return Tournament::where('is_active', true)
            ->orderBy('title_ru')
            ->get(['id', 'title_ru', 'title_kk', 'title_en']);
    }

    public function getSeasonOptions()
    {
        return Season::orderBy('title_ru')
            ->get(['id', 'title_ru', 'title_kk', 'title_en']);
    }

    public function getStadiumOptions()
    {
        return Stadium::where('is_active', true)
            ->orderBy('title_ru')
            ->get(['id', 'title_ru', 'title_kk', 'title_en', 'city_id']);
    }

    public function getClubOptions()
    {
        return Club::where('is_active', true)
            ->orderBy('short_name_ru')
            ->get(['id', 'short_name_ru', 'short_name_kk', 'short_name_en']);
    }

    public function getOperationOptions()
    {
        return Operation::where('is_active', true)
            ->orderBy('title_ru')
            ->get(['id', 'title_ru', 'title_kk', 'title_en']);
    }

    public function getJudgeTypeOptions()
    {
        return JudgeType::where('is_active', true)
            ->orderBy('title_ru')
            ->get(['id', 'title_ru', 'title_kk', 'title_en']);
    }

    public function getLogistUsers()
    {
        return User::where('is_active', true)
            ->whereHas('role', function ($q) {
                $q->where('value', RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN);
            })
            ->orderBy('last_name')
            ->get(['id', 'last_name', 'first_name', 'patronymic']);
    }

    public function render()
    {
        $query = MatchModel::withTrashed()
            ->with(['tournament', 'season', 'ownerClub', 'guestClub', 'winner', 'stadium', 'operation'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('tournament', function ($tq) {
                        $tq->where('title_ru', 'like', "%{$this->search}%")
                           ->orWhere('title_kk', 'like', "%{$this->search}%")
                           ->orWhere('title_en', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('ownerClub', function ($cq) {
                        $cq->where('short_name_ru', 'like', "%{$this->search}%")
                           ->orWhere('short_name_kk', 'like', "%{$this->search}%")
                           ->orWhere('short_name_en', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('guestClub', function ($cq) {
                        $cq->where('short_name_ru', 'like', "%{$this->search}%")
                           ->orWhere('short_name_kk', 'like', "%{$this->search}%")
                           ->orWhere('short_name_en', 'like', "%{$this->search}%");
                    });
                });
            })
            ->when($this->filter_tournament_id, function ($query) {
                $query->where('tournament_id', $this->filter_tournament_id);
            })
            ->when($this->filter_season_id, function ($query) {
                $query->where('season_id', $this->filter_season_id);
            })
            ->when($this->filter_is_active !== null, function ($query) {
                $query->where('is_active', $this->filter_is_active);
            })
            ->when($this->filter_is_finished !== null, function ($query) {
                $query->where('is_finished', $this->filter_is_finished);
            })
            ->when($this->filter_is_canceled !== null, function ($query) {
                $query->where('is_canceled', $this->filter_is_canceled);
            });

        // Sorting
        if ($this->sortField === 'tournament') {
            $query->join('tournaments', 'matches.tournament_id', '=', 'tournaments.id')
                  ->orderBy('tournaments.title_ru', $this->sortDirection)
                  ->select('matches.*');
        } elseif ($this->sortField === 'start_at') {
            $query->orderBy('start_at', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $matches = $query->paginate(10);

        return view('livewire.admin.match-management', [
            'matches' => $matches,
        ]);
    }
}

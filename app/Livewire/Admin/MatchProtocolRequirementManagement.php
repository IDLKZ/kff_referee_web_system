<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\JudgeType;
use App\Models\MatchModel;
use App\Models\MatchProtocolRequirement;
use App\Models\Tournament;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Match Protocol Requirements')]
class MatchProtocolRequirementManagement extends Component
{
    use WithPagination;
    public string $search = '';
    public ?int $filterTournamentId = null;
    public ?int $filterJudgeTypeId = null;
    public ?int $filterIsRequired = null;

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingId = null;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public ?int $tournament_id = null;
    public ?int $match_id = null;
    public ?int $judge_type_id = null;
    public bool $is_required = false;
    public string $info_ru = '';
    public string $info_kk = '';
    public string $info_en = '';
    public string $extensions = '';

    // Delete target
    public ?int $deletingId = null;
    public string $deletingName = '';

    protected function rules(): array
    {
        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'tournament_id' => ['required', 'exists:tournaments,id'],
            'match_id' => ['nullable', 'exists:matches,id'],
            'judge_type_id' => ['required', 'exists:judge_types,id'],
            'is_required' => ['boolean'],
            'info_ru' => ['nullable', 'string'],
            'info_kk' => ['nullable', 'string'],
            'info_en' => ['nullable', 'string'],
            'extensions' => ['nullable', 'string'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_UPDATE);
        $requirement = MatchProtocolRequirement::findOrFail($id);

        $this->editingId = $requirement->id;
        $this->title_ru = $requirement->title_ru;
        $this->title_kk = $requirement->title_kk ?? '';
        $this->title_en = $requirement->title_en ?? '';
        $this->tournament_id = $requirement->tournament_id;
        $this->match_id = $requirement->match_id;
        $this->judge_type_id = $requirement->judge_type_id;
        $this->is_required = $requirement->is_required;
        $this->info_ru = $requirement->info_ru ?? '';
        $this->info_kk = $requirement->info_kk ?? '';
        $this->info_en = $requirement->info_en ?? '';
        $this->extensions = is_array($requirement->extensions) ? json_encode($requirement->extensions, JSON_PRETTY_PRINT) : ($requirement->extensions ?? '');
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_CREATE);
        }

        $this->validate();

        // Parse extensions JSON
        $extensionsData = [];
        if (!empty($this->extensions)) {
            $decoded = json_decode($this->extensions, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $extensionsData = $decoded;
            } else {
                $this->addError('extensions', __('validation.invalid_json'));
                return;
            }
        }

        $data = [
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'tournament_id' => $this->tournament_id,
            'match_id' => $this->match_id ?: null,
            'judge_type_id' => $this->judge_type_id,
            'is_required' => $this->is_required,
            'info_ru' => $this->info_ru ?: null,
            'info_kk' => $this->info_kk ?: null,
            'info_en' => $this->info_en ?: null,
            'extensions' => $extensionsData,
        ];

        if ($this->isEditing) {
            MatchProtocolRequirement::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            MatchProtocolRequirement::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_DELETE);
        $requirement = MatchProtocolRequirement::findOrFail($id);
        $this->deletingId = $requirement->id;
        $this->deletingName = $requirement->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_DELETE);
        MatchProtocolRequirement::findOrFail($this->deletingId)->delete();

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

    public function clearSearch(): void
    {
        $this->search = '';
        $this->filterTournamentId = null;
        $this->filterJudgeTypeId = null;
        $this->filterIsRequired = null;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterTournamentId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterJudgeTypeId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterIsRequired(): void
    {
        $this->resetPage();
    }

    public function getTournaments(): Collection
    {
        return Tournament::orderBy('title_ru')->get();
    }

    public function getJudgeTypes(): Collection
    {
        return JudgeType::orderBy('title_ru')->get();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title_ru = '';
        $this->title_kk = '';
        $this->title_en = '';
        $this->tournament_id = null;
        $this->match_id = null;
        $this->judge_type_id = null;
        $this->is_required = false;
        $this->info_ru = '';
        $this->info_kk = '';
        $this->info_en = '';
        $this->extensions = '';
        $this->resetValidation();
    }

    private function checkPermission(string $permission): void
    {
        if (!auth()->user()->hasPermission($permission)) {
            abort(403);
        }
    }

    public function render()
    {
        $query = MatchProtocolRequirement::query()
            ->with(['tournament', 'judge_type'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title_ru', 'like', "%{$this->search}%")
                      ->orWhere('title_kk', 'like', "%{$this->search}%")
                      ->orWhere('title_en', 'like', "%{$this->search}%")
                      ->orWhere('info_ru', 'like', "%{$this->search}%")
                      ->orWhere('info_kk', 'like', "%{$this->search}%")
                      ->orWhere('info_en', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterTournamentId, function ($query) {
                $query->where('tournament_id', $this->filterTournamentId);
            })
            ->when($this->filterJudgeTypeId, function ($query) {
                $query->where('judge_type_id', $this->filterJudgeTypeId);
            })
            ->when($this->filterIsRequired !== null, function ($query) {
                $query->where('is_required', $this->filterIsRequired === 1);
            });

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $requirements = $query->paginate(10);

        return view('livewire.admin.match-protocol-requirement-management', [
            'requirements' => $requirements,
            'tournaments' => $this->getTournaments(),
            'judgeTypes' => $this->getJudgeTypes(),
        ]);
    }
}

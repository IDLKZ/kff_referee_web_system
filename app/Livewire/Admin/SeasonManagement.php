<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Http\Requests\Season\SeasonCreateRequest;
use App\Http\Requests\Season\SeasonUpdateRequest;
use App\Models\Season;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Seasons')]
class SeasonManagement extends Component
{
    use WithPagination;

    // Search & Filter
    public string $search = '';

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
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public string $value = '';
    public ?string $start_at = '';
    public ?string $end_at = '';

    // Delete target
    public ?int $deletingId = null;
    public string $deletingInfo = '';

    /**
     * Get validation rules from Form Request classes
     */
    protected function rules(): array
    {
        if ($this->isEditing) {
            $request = new SeasonUpdateRequest();
            $request->setEditingId($this->editingId);
            return $request->rules();
        }

        return (new SeasonCreateRequest())->rules();
    }

    /**
     * Get validation messages from Form Request classes
     */
    protected function messages(): array
    {
        if ($this->isEditing) {
            return (new SeasonUpdateRequest())->messages();
        }

        return (new SeasonCreateRequest())->messages();
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::SEASONS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::SEASONS_UPDATE);
        $season = Season::findOrFail($id);

        $this->editingId = $season->id;
        $this->title_ru = $season->title_ru;
        $this->title_kk = $season->title_kk ?? '';
        $this->title_en = $season->title_en ?? '';
        $this->value = $season->value;
        $this->start_at = $season->start_at?->format('Y-m-d') ?? '';
        $this->end_at = $season->end_at?->format('Y-m-d') ?? '';
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::SEASONS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::SEASONS_CREATE);
        }

        $this->validate();

        $data = [
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'value' => $this->value,
            'start_at' => $this->start_at ?: null,
            'end_at' => $this->end_at ?: null,
        ];

        if ($this->isEditing) {
            Season::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            Season::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::SEASONS_DELETE);
        $season = Season::findOrFail($id);

        $this->deletingId = $season->id;
        $this->deletingInfo = $season->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::SEASONS_DELETE);

        Season::findOrFail($this->deletingId)->delete();

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
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->isEditing = false;
        $this->title_ru = '';
        $this->title_kk = '';
        $this->title_en = '';
        $this->value = '';
        $this->start_at = '';
        $this->end_at = '';
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
        $query = Season::when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('title_ru', 'like', "%{$this->search}%")
                  ->orWhere('title_kk', 'like', "%{$this->search}%")
                  ->orWhere('title_en', 'like', "%{$this->search}%")
                  ->orWhere('value', 'like', "%{$this->search}%");
            });
        });

        $seasons = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.season-management', [
            'seasons' => $seasons,
        ]);
    }
}

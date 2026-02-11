<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Facility;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Facilities')]
class FacilityManagement extends Component
{
    use WithPagination;

    // Search & Filters
    public string $search = '';
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
    public ?string $title_kk = null;
    public ?string $title_en = null;

    // Delete target
    public ?int $deletingFacilityId = null;
    public string $deletingFacilityName = '';

    protected function rules(): array
    {
        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::FACILITIES_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::FACILITIES_UPDATE);
        $facility = Facility::findOrFail($id);

        $this->editingId = $facility->id;
        $this->title_ru = $facility->title_ru;
        $this->title_kk = $facility->title_kk;
        $this->title_en = $facility->title_en;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::FACILITIES_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::FACILITIES_CREATE);
        }

        $this->validate();

        $data = [
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
        ];

        if ($this->isEditing) {
            Facility::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            Facility::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::FACILITIES_DELETE);
        $facility = Facility::findOrFail($id);

        $this->deletingFacilityId = $facility->id;
        $this->deletingFacilityName = $facility->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::FACILITIES_DELETE);
        Facility::findOrFail($this->deletingFacilityId)->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingFacilityId = null;
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
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title_ru = '';
        $this->title_kk = null;
        $this->title_en = null;
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
        $query = Facility::query()
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title_ru', 'like', $searchTerm)
                      ->orWhere('title_kk', 'like', $searchTerm)
                      ->orWhere('title_en', 'like', $searchTerm);
                });
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $facilities = $query->paginate(10);

        return view('livewire.admin.facility-management', [
            'facilities' => $facilities,
        ]);
    }
}

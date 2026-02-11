<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Http\Requests\TransportType\TransportTypeCreateRequest;
use App\Http\Requests\TransportType\TransportTypeUpdateRequest;
use App\Models\TransportType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Transport Types')]
class TransportTypeManagement extends Component
{
    use WithPagination;

    // Search & Filter
    public string $search = '';
    public ?bool $filter_is_active = null;

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
    public bool $is_active = true;

    // Delete target
    public ?int $deletingId = null;
    public string $deletingInfo = '';

    /**
     * Get validation rules from Form Request classes
     */
    protected function rules(): array
    {
        if ($this->isEditing) {
            $request = new TransportTypeUpdateRequest();
            $request->setEditingId($this->editingId);
            return $request->rules();
        }

        return (new TransportTypeCreateRequest())->rules();
    }

    /**
     * Get validation messages from Form Request classes
     */
    protected function messages(): array
    {
        if ($this->isEditing) {
            return (new TransportTypeUpdateRequest())->messages();
        }

        return (new TransportTypeCreateRequest())->messages();
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::TRANSPORT_TYPES_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::TRANSPORT_TYPES_UPDATE);
        $transportType = TransportType::findOrFail($id);

        $this->editingId = $transportType->id;
        $this->title_ru = $transportType->title_ru;
        $this->title_kk = $transportType->title_kk ?? '';
        $this->title_en = $transportType->title_en ?? '';
        $this->value = $transportType->value;
        $this->is_active = $transportType->is_active ?? true;

        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::TRANSPORT_TYPES_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::TRANSPORT_TYPES_CREATE);
        }

        $this->validate();

        $data = [
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'value' => $this->value,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            TransportType::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            TransportType::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::TRANSPORT_TYPES_DELETE);
        $transportType = TransportType::find($id);

        $this->deletingId = $transportType->id;
        $this->deletingInfo = $transportType->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::TRANSPORT_TYPES_DELETE);

        $transportType = TransportType::findOrFail($this->deletingId);
        $transportType->delete();

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
        $this->filter_is_active = null;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterIsActive(): void
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
        $this->is_active = true;
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
        $query = TransportType::when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('title_ru', 'like', "%{$this->search}%")
                  ->orWhere('title_kk', 'like', "%{$this->search}%")
                  ->orWhere('title_en', 'like', "%{$this->search}%")
                  ->orWhere('value', 'like', "%{$this->search}%");
            });
        })
        ->when($this->filter_is_active !== null, function ($query) {
            $query->where('is_active', $this->filter_is_active);
        });

        $transportTypes = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.transport-type-management', [
            'transportTypes' => $transportTypes,
        ]);
    }
}

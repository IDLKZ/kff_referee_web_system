<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Permission;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layout')]
#[Title('Permissions')]
class PermissionManagement extends Component
{
    public string $search = '';

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingPermissionId = null;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public string $value = '';

    // Delete target
    public ?int $deletingPermissionId = null;
    public string $deletingPermissionName = '';

    protected function rules(): array
    {
        $uniqueRule = 'unique:permissions,value';
        if ($this->editingPermissionId) {
            $uniqueRule .= ',' . $this->editingPermissionId;
        }

        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255', $uniqueRule],
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::PERMISSIONS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::PERMISSIONS_UPDATE);
        $permission = Permission::findOrFail($id);

        $this->editingPermissionId = $permission->id;
        $this->title_ru = $permission->title_ru;
        $this->title_kk = $permission->title_kk ?? '';
        $this->title_en = $permission->title_en ?? '';
        $this->value = $permission->value;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::PERMISSIONS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::PERMISSIONS_CREATE);
        }

        $this->validate();

        $data = [
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'value' => $this->value,
        ];

        if ($this->isEditing) {
            Permission::findOrFail($this->editingPermissionId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            Permission::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::PERMISSIONS_DELETE);
        $permission = Permission::findOrFail($id);
        $this->deletingPermissionId = $permission->id;
        $this->deletingPermissionName = $permission->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::PERMISSIONS_DELETE);
        Permission::findOrFail($this->deletingPermissionId)->delete();

        toastr()->success(__('crud.deleted_success'));

        $this->showDeleteModal = false;
        $this->deletingPermissionId = null;
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
    }

    private function resetForm(): void
    {
        $this->editingPermissionId = null;
        $this->title_ru = '';
        $this->title_kk = '';
        $this->title_en = '';
        $this->value = '';
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
        $query = Permission::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title_ru', 'like', "%{$this->search}%")
                      ->orWhere('title_kk', 'like', "%{$this->search}%")
                      ->orWhere('title_en', 'like', "%{$this->search}%")
                      ->orWhere('value', 'like', "%{$this->search}%");
                });
            });

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $permissions = $query->paginate(10);

        return view('livewire.admin.permission-management', [
            'permissions' => $permissions,
        ]);
    }
}

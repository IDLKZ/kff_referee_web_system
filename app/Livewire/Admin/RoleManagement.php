<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Constants\RoleConstants;
use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layout')]
#[Title('Roles')]
class RoleManagement extends Component
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
    public ?int $editingRoleId = null;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public string $value = '';
    public string $group = '';
    public bool $can_register = false;
    public bool $is_active = true;

    // Delete target
    public ?int $deletingRoleId = null;
    public string $deletingRoleName = '';

    protected function rules(): array
    {
        $uniqueRule = 'unique:roles,value';
        if ($this->editingRoleId) {
            $uniqueRule .= ',' . $this->editingRoleId;
        }

        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255', $uniqueRule],
            'group' => ['required', 'string', 'max:255'],
            'can_register' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::ROLES_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::ROLES_UPDATE);
        $role = Role::findOrFail($id);

        $this->editingRoleId = $role->id;
        $this->title_ru = $role->title_ru;
        $this->title_kk = $role->title_kk ?? '';
        $this->title_en = $role->title_en ?? '';
        $this->value = $role->value;
        $this->group = $role->group;
        $this->can_register = $role->can_register;
        $this->is_active = $role->is_active;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::ROLES_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::ROLES_CREATE);
        }

        $this->validate();

        $data = [
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'value' => $this->value,
            'group' => $this->group,
            'can_register' => $this->can_register,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            Role::findOrFail($this->editingRoleId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            Role::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::ROLES_DELETE);
        $role = Role::findOrFail($id);
        $this->deletingRoleId = $role->id;
        $this->deletingRoleName = $role->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::ROLES_DELETE);
        Role::findOrFail($this->deletingRoleId)->delete();

        toastr()->success(__('crud.deleted_success'));

        $this->showDeleteModal = false;
        $this->deletingRoleId = null;
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

    public function gotoPage($page): void
    {
        $this->setPage($page);
    }

    public function getGroupOptions(): array
    {
        return [
            RoleConstants::ADMINISTRATOR_GROUP => RoleConstants::ADMINISTRATOR_GROUP,
            RoleConstants::KFF_PFLK_GROUP => RoleConstants::KFF_PFLK_GROUP,
            RoleConstants::REFEREE_GROUP => RoleConstants::REFEREE_GROUP,
        ];
    }

    private function resetForm(): void
    {
        $this->editingRoleId = null;
        $this->title_ru = '';
        $this->title_kk = '';
        $this->title_en = '';
        $this->value = '';
        $this->group = '';
        $this->can_register = false;
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
        $query = Role::query()
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

        $roles = $query->paginate(10);

        return view('livewire.admin.role-management', [
            'roles' => $roles,
        ]);
    }
}

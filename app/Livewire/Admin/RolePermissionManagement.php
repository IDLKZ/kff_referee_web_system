<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Permission;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layout')]
#[Title('Role Permissions')]
class RolePermissionManagement extends Component
{
    public string $search = '';

    // Sorting
    public string $sortField = 'role_id';
    public string $sortDirection = 'asc';

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;

    // Form fields
    public ?int $role_id = null;
    public ?int $permission_id = null;

    // Delete target
    public ?int $deletingRoleId = null;
    public ?int $deletingPermissionId = null;
    public string $deletingInfo = '';

    protected function rules(): array
    {
        return [
            'role_id' => ['required', 'exists:roles,id'],
            'permission_id' => ['required', 'exists:permissions,id'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::ROLE_PERMISSIONS_CREATE);
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->checkPermission(PermissionConstants::ROLE_PERMISSIONS_CREATE);

        $this->validate();

        // Check if already exists
        $exists = RolePermission::where('role_id', $this->role_id)
            ->where('permission_id', $this->permission_id)
            ->exists();

        if ($exists) {
            toastr()->error(__('crud.role_permission_exists'));
            return;
        }

        RolePermission::create([
            'role_id' => $this->role_id,
            'permission_id' => $this->permission_id,
        ]);

        toastr()->success(__('crud.created_success'));
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $roleId, int $permissionId): void
    {
        $this->checkPermission(PermissionConstants::ROLE_PERMISSIONS_DELETE);

        $rolePermission = RolePermission::where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->firstOrFail();

        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);

        $this->deletingRoleId = $roleId;
        $this->deletingPermissionId = $permissionId;
        $this->deletingInfo = "{$role->title_ru} → {$permission->title_ru}";
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::ROLE_PERMISSIONS_DELETE);

        RolePermission::where('role_id', $this->deletingRoleId)
            ->where('permission_id', $this->deletingPermissionId)
            ->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingRoleId = null;
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

    public function getRoleOptions(): array
    {
        return Role::orderBy('title_ru')->pluck('title_ru', 'id')->toArray();
    }

    public function getPermissionOptions(): array
    {
        return Permission::orderBy('title_ru')->pluck('title_ru', 'id')->toArray();
    }

    private function resetForm(): void
    {
        $this->role_id = null;
        $this->permission_id = null;
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
        $query = RolePermission::with(['role', 'permission'])
            ->when($this->search, function ($query) {
                $query->whereHas('role', function ($q) {
                    $q->where('title_ru', 'like', "%{$this->search}%")
                      ->orWhere('title_kk', 'like', "%{$this->search}%")
                      ->orWhere('title_en', 'like', "%{$this->search}%")
                      ->orWhere('value', 'like', "%{$this->search}%");
                })
                ->orWhereHas('permission', function ($q) {
                    $q->where('title_ru', 'like', "%{$this->search}%")
                      ->orWhere('title_kk', 'like', "%{$this->search}%")
                      ->orWhere('title_en', 'like', "%{$this->search}%")
                      ->orWhere('value', 'like', "%{$this->search}%");
                });
            });

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $rolePermissions = $query->paginate(10);

        return view('livewire.admin.role-permission-management', [
            'rolePermissions' => $rolePermissions,
        ]);
    }
}

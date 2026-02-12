<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Operation;
use App\Models\Role;
use App\Models\RoleOperation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Role Operations')]
class RoleOperationManagement extends Component
{
    use WithPagination;

    // --- Поиск и фильтры ---
    public string $search = '';
    public ?int $filter_role_id = null;
    public ?int $filter_operation_id = null;
    public string $sortField = 'role_id';
    public string $sortDirection = 'asc';

    // --- Состояние модальных окон ---
    public bool $showCreateModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;

    // --- Поля формы создания ---
    public ?int $form_role_id = null;
    public ?int $form_operation_id = null;

    // --- Цель для удаления ---
    public ?array $deletingIds = null;
    public string $deletingInfo = '';

    /**
     * Правила валидации
     */
    protected function rules(): array
    {
        return [
            'form_role_id' => ['required', 'exists:roles,id'],
            'form_operation_id' => ['required', 'exists:operations,id'],
            'form_role_id' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) {
                    // Проверка на дубликаты
                    $exists = RoleOperation::where('role_id', $value)
                        ->where('operation_id', $this->form_operation_id)
                        ->exists();

                    if ($exists) {
                        $role = Role::find($value);
                        $operation = Operation::find($this->form_operation_id);
                        $fail('role_operation_exists', [
                            'role' => $role ? $role->title_ru : '',
                            'operation' => $operation ? $operation->title_ru : '',
                        ]);
                    }
                },
            ],
            'form_operation_id' => ['required', 'exists:operations,id'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'form_role_id' => __('crud.role'),
            'form_operation_id' => __('crud.operation'),
        ];
    }

    protected function messages(): array
    {
        return [
            'form_role_id.role_operation_exists' => __('crud.role_operation_exists'),
        ];
    }

    // --- Сброс пагинации при изменении фильтров ---

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterRoleId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterOperationId(): void
    {
        $this->resetPage();
    }

    // --- Создание ---

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::ROLE_OPERATIONS_CREATE);
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function save(): void
    {
        $this->checkPermission(PermissionConstants::ROLE_OPERATIONS_CREATE);
        $this->validate();

        RoleOperation::create([
            'role_id' => $this->form_role_id,
            'operation_id' => $this->form_operation_id,
        ]);

        toastr()->success(__('crud.role_operation_created_success'));
        $this->showCreateModal = false;
        $this->resetForm();
    }

    // --- Удаление ---

    public function confirmDelete(int $roleId, int $operationId): void
    {
        $this->checkPermission(PermissionConstants::ROLE_OPERATIONS_DELETE);

        $role = Role::find($roleId);
        $operation = Operation::find($operationId);

        $this->deletingIds = ['role_id' => $roleId, 'operation_id' => $operationId];
        $this->deletingInfo = ($role ? $role->title_ru : '') . ' / ' . ($operation ? $operation->title_ru : '');
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::ROLE_OPERATIONS_DELETE);

        RoleOperation::where('role_id', $this->deletingIds['role_id'])
            ->where('operation_id', $this->deletingIds['operation_id'])
            ->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingIds = null;
    }

    // --- Сортировка ---

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // --- Поиск / Фильтр ---

    public function toggleSearchModal(): void
    {
        $this->showSearchModal = !$this->showSearchModal;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filter_role_id = null;
        $this->filter_operation_id = null;
        $this->resetPage();
    }

    // --- Опции для выпадающих списков ---

    public function getRoleOptions(): array
    {
        return Role::orderBy('title_ru')
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(function ($role) {
                return [$role->id => $role->title_ru . ' (' . $role->value . ')'];
            })
            ->toArray();
    }

    public function getOperationOptions(): array
    {
        return Operation::with('category_operation')
            ->where('is_active', true)
            ->orderBy('category_id')
            ->orderBy('title_ru')
            ->get()
            ->mapWithKeys(function ($op) {
                $categoryName = $op->category_operation ? $op->category_operation->title_ru : '';
                $label = $categoryName
                    ? "{$categoryName} > {$op->title_ru}"
                    : $op->title_ru;
                return [$op->id => $label];
            })
            ->toArray();
    }

    // --- Сервисные методы ---

    private function resetForm(): void
    {
        $this->form_role_id = null;
        $this->form_operation_id = null;
        $this->resetValidation();
    }

    private function checkPermission(string $permission): void
    {
        if (!auth()->user()->hasPermission($permission)) {
            abort(403);
        }
    }

    // --- Рендер ---

    public function render()
    {
        $query = RoleOperation::with(['role', 'operation', 'operation.category_operation'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->whereHas('role', function ($q) use ($searchTerm) {
                        $q->where('title_ru', 'like', $searchTerm)
                          ->orWhere('title_kk', 'like', $searchTerm)
                          ->orWhere('title_en', 'like', $searchTerm)
                          ->orWhere('value', 'like', $searchTerm);
                    })
                    ->orWhereHas('operation', function ($q) use ($searchTerm) {
                        $q->where('title_ru', 'like', $searchTerm)
                          ->orWhere('title_kk', 'like', $searchTerm)
                          ->orWhere('title_en', 'like', $searchTerm)
                          ->orWhere('value', 'like', $searchTerm);
                    });
            })
            ->when($this->filter_role_id, function ($query) {
                $query->where('role_id', $this->filter_role_id);
            })
            ->when($this->filter_operation_id, function ($query) {
                $query->where('operation_id', $this->filter_operation_id);
            });

        // Сортировка
        if ($this->sortField === 'role_id') {
            $query->orderBy('role_id', $this->sortDirection)->orderBy('operation_id');
        } elseif ($this->sortField === 'operation_id') {
            $query->orderBy('operation_id', $this->sortDirection)->orderBy('role_id');
        } elseif ($this->sortField === 'role_title') {
            $query->join('roles', 'role_operations.role_id', '=', 'roles.id')
                ->orderBy('roles.title_ru', $this->sortDirection)
                ->orderBy('operations.title_ru');
        } elseif ($this->sortField === 'operation_title') {
            $query->join('operations', 'role_operations.operation_id', '=', 'operations.id')
                ->orderBy('operations.title_ru', $this->sortDirection)
                ->orderBy('roles.title_ru');
        }

        $roleOperations = $query->paginate(15);

        return view('livewire.admin.role-operation-management', [
            'roleOperations' => $roleOperations,
        ]);
    }
}

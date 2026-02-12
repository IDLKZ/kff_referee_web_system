<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\CategoryOperation;
use App\Models\Operation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Operations')]
class OperationManagement extends Component
{
    use WithPagination;

    // --- Поиск и фильтры ---
    public string $search = '';
    public ?int $filter_category_id = null;
    public ?bool $filter_is_active = null;
    public ?bool $filter_can_reject = null;
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // --- Состояние модальных окон ---
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // --- Поля формы ---
    public ?int $editingId = null;
    public ?int $category_id = null;
    public string $title_ru = '';
    public ?string $title_kk = null;
    public ?string $title_en = null;
    public ?string $description_ru = null;
    public ?string $description_kk = null;
    public ?string $description_en = null;
    public string $value = '';
    public bool $is_first = false;
    public bool $is_last = false;
    public bool $can_reject = false;
    public bool $is_active = true;
    public ?int $result = null;
    public ?int $previous_id = null;
    public ?int $next_id = null;
    public ?int $on_reject_id = null;

    // --- Цель для удаления ---
    public ?int $deletingId = null;
    public string $deletingName = '';

    /**
     * Правила валидации
     */
    protected function rules(): array
    {
        $uniqueRule = 'unique:operations,value';
        if ($this->editingId) {
            $uniqueRule .= ',' . $this->editingId;
        }

        return [
            'category_id' => ['required', 'exists:category_operations,id'],
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string', 'max:2000'],
            'description_kk' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'value' => ['required', 'string', 'max:280', $uniqueRule],
            'is_first' => ['boolean'],
            'is_last' => ['boolean'],
            'can_reject' => ['boolean'],
            'is_active' => ['boolean'],
            'result' => ['nullable', 'integer', 'min:0'],
            'previous_id' => ['nullable', 'exists:operations,id' . ($this->editingId ? ',' . $this->editingId : '')],
            'next_id' => ['nullable', 'exists:operations,id' . ($this->editingId ? ',' . $this->editingId : '')],
            'on_reject_id' => ['nullable', 'exists:operations,id' . ($this->editingId ? ',' . $this->editingId : '')],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'category_id' => __('crud.category'),
            'title_ru' => __('crud.title_ru'),
            'title_kk' => __('crud.title_kk'),
            'title_en' => __('crud.title_en'),
            'description_ru' => __('crud.description_ru'),
            'description_kk' => __('crud.description_kk'),
            'description_en' => __('crud.description_en'),
            'value' => __('crud.value'),
            'is_first' => __('crud.is_first_label'),
            'is_last' => __('crud.is_last_label'),
            'can_reject' => __('crud.can_reject'),
            'is_active' => __('crud.is_active'),
            'result' => __('crud.result'),
            'previous_id' => __('crud.previous_operation'),
            'next_id' => __('crud.next_operation'),
            'on_reject_id' => __('crud.on_reject_operation'),
        ];
    }

    // --- Сброс пагинации при изменении фильтров ---

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterIsActive(): void
    {
        $this->resetPage();
    }

    public function updatedFilterCanReject(): void
    {
        $this->resetPage();
    }

    // --- Создание ---

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::OPERATIONS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    // --- Редактирование ---

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::OPERATIONS_UPDATE);
        $operation = Operation::with(['category_operation', 'operation', 'operations'])->findOrFail($id);

        $this->editingId = $operation->id;
        $this->category_id = $operation->category_id;
        $this->title_ru = $operation->title_ru;
        $this->title_kk = $operation->title_kk;
        $this->title_en = $operation->title_en;
        $this->description_ru = $operation->description_ru;
        $this->description_kk = $operation->description_kk;
        $this->description_en = $operation->description_en;
        $this->value = $operation->value;
        $this->is_first = $operation->is_first;
        $this->is_last = $operation->is_last;
        $this->can_reject = $operation->can_reject;
        $this->is_active = $operation->is_active;
        $this->result = $operation->result;
        $this->previous_id = $operation->previous_id;
        $this->next_id = $operation->next_id;
        $this->on_reject_id = $operation->on_reject_id;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    // --- Сохранение (создание/обновление) ---

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::OPERATIONS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::OPERATIONS_CREATE);
        }

        $this->validate();

        $data = [
            'category_id' => $this->category_id,
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'description_ru' => $this->description_ru ?: null,
            'description_kk' => $this->description_kk ?: null,
            'description_en' => $this->description_en ?: null,
            'value' => $this->value,
            'is_first' => $this->is_first,
            'is_last' => $this->is_last,
            'can_reject' => $this->can_reject,
            'is_active' => $this->is_active,
            'result' => $this->result ?: null,
            'previous_id' => $this->previous_id ?: null,
            'next_id' => $this->next_id ?: null,
            'on_reject_id' => $this->on_reject_id ?: null,
        ];

        if ($this->isEditing) {
            Operation::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            Operation::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    // --- Удаление ---

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::OPERATIONS_DELETE);
        $operation = Operation::findOrFail($id);

        $this->deletingId = $operation->id;
        $this->deletingName = $operation->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::OPERATIONS_DELETE);
        Operation::findOrFail($this->deletingId)->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingId = null;
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
        $this->filter_category_id = null;
        $this->filter_is_active = null;
        $this->filter_can_reject = null;
        $this->resetPage();
    }

    // --- Опции для выпадающих списков ---

    /**
     * Получить список категорий операций
     */
    public function getCategoryOptions(): array
    {
        return CategoryOperation::orderBy('title_ru')
            ->get()
            ->mapWithKeys(function ($cat) {
                return [$cat->id => $cat->title_ru];
            })
            ->toArray();
    }

    /**
     * Получить список операций для связей (previous/next/on_reject)
     * Исключаем текущую операцию (при редактировании)
     */
    public function getOperationOptions(): array
    {
        return Operation::when($this->editingId, function ($q) {
                $q->where('id', '!=', $this->editingId);
            })
            ->with('category_operation')
            ->orderBy('category_id')
            ->orderBy('title_ru')
            ->get()
            ->mapWithKeys(function ($op) {
                $categoryName = $op->category_operation ? $op->category_operation->title_ru : '';
                $label = $categoryName
                    ? "{$categoryName} > {$op->title_ru} ({$op->value})"
                    : "{$op->title_ru} ({$op->value})";
                return [$op->id => $label];
            })
            ->toArray();
    }

    /**
     * Получить список операций с возможностью отклонения
     */
    public function getRejectableOperationOptions(): array
    {
        return Operation::where('can_reject', true)
            ->when($this->editingId, function ($q) {
                $q->where('id', '!=', $this->editingId);
            })
            ->with('category_operation')
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
        $this->editingId = null;
        $this->category_id = null;
        $this->title_ru = '';
        $this->title_kk = null;
        $this->title_en = null;
        $this->description_ru = null;
        $this->description_kk = null;
        $this->description_en = null;
        $this->value = '';
        $this->is_first = false;
        $this->is_last = false;
        $this->can_reject = false;
        $this->is_active = true;
        $this->result = null;
        $this->previous_id = null;
        $this->next_id = null;
        $this->on_reject_id = null;
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
        $query = Operation::with(['category_operation', 'operation', 'operations'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title_ru', 'like', $searchTerm)
                      ->orWhere('title_kk', 'like', $searchTerm)
                      ->orWhere('title_en', 'like', $searchTerm)
                      ->orWhere('description_ru', 'like', $searchTerm)
                      ->orWhere('description_kk', 'like', $searchTerm)
                      ->orWhere('description_en', 'like', $searchTerm)
                      ->orWhere('value', 'like', $searchTerm);
                });
            })
            ->when($this->filter_category_id, function ($query) {
                $query->where('category_id', $this->filter_category_id);
            })
            ->when($this->filter_is_active !== null, function ($query) {
                $query->where('is_active', $this->filter_is_active);
            })
            ->when($this->filter_can_reject !== null, function ($query) {
                $query->where('can_reject', $this->filter_can_reject);
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $operations = $query->paginate(10);

        return view('livewire.admin.operation-management', [
            'operations' => $operations,
        ]);
    }
}

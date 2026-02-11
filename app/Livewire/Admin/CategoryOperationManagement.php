<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\CategoryOperation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Category Operations')]
class CategoryOperationManagement extends Component
{
    use WithPagination;

    // --- Поиск и фильтры ---
    public string $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // --- Состояние модальных окон ---
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // --- Поля формы ---
    public ?int $editingId = null;
    public string $title_ru = '';
    public ?string $title_kk = null;
    public ?string $title_en = null;
    public string $value = '';
    public bool $is_first = false;
    public bool $is_last = false;
    public ?int $previous_id = null;
    public ?int $next_id = null;

    // --- Цель для удаления ---
    public ?int $deletingId = null;
    public string $deletingName = '';

    /**
     * Правила валидации
     */
    protected function rules(): array
    {
        $uniqueRule = 'unique:category_operations,value';
        if ($this->editingId) {
            $uniqueRule .= ',' . $this->editingId;
        }

        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:280', $uniqueRule],
            'is_first' => ['boolean'],
            'is_last' => ['boolean'],
            'previous_id' => ['nullable', 'exists:category_operations,id'],
            'next_id' => ['nullable', 'exists:category_operations,id'],
        ];
    }

    // --- Сброс пагинации при изменении фильтров ---

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // --- Создание ---

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::CATEGORY_OPERATIONS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    // --- Редактирование ---

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::CATEGORY_OPERATIONS_UPDATE);
        $category = CategoryOperation::findOrFail($id);

        $this->editingId = $category->id;
        $this->title_ru = $category->title_ru;
        $this->title_kk = $category->title_kk;
        $this->title_en = $category->title_en;
        $this->value = $category->value;
        $this->is_first = $category->is_first;
        $this->is_last = $category->is_last;
        $this->previous_id = $category->previous_id;
        $this->next_id = $category->next_id;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    // --- Сохранение (создание/обновление) ---

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::CATEGORY_OPERATIONS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::CATEGORY_OPERATIONS_CREATE);
        }

        $this->validate();

        $data = [
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'value' => $this->value,
            'is_first' => $this->is_first,
            'is_last' => $this->is_last,
            'previous_id' => $this->previous_id ?: null,
            'next_id' => $this->next_id ?: null,
        ];

        if ($this->isEditing) {
            CategoryOperation::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            CategoryOperation::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    // --- Удаление ---

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::CATEGORY_OPERATIONS_DELETE);
        $category = CategoryOperation::findOrFail($id);

        $this->deletingId = $category->id;
        $this->deletingName = $category->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::CATEGORY_OPERATIONS_DELETE);
        CategoryOperation::findOrFail($this->deletingId)->delete();

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
        $this->resetPage();
    }

    // --- Опции для выпадающих списков (предыдущая/следующая) ---

    /**
     * Получить список категорий для выбора previous/next
     * Исключаем текущую категорию (при редактировании)
     */
    public function getCategoryOptions(): array
    {
        return CategoryOperation::when($this->editingId, function ($q) {
                $q->where('id', '!=', $this->editingId);
            })
            ->orderBy('title_ru')
            ->get()
            ->mapWithKeys(function ($cat) {
                return [$cat->id => $cat->title_ru . ' (' . $cat->value . ')'];
            })
            ->toArray();
    }

    // --- Сервисные методы ---

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title_ru = '';
        $this->title_kk = null;
        $this->title_en = null;
        $this->value = '';
        $this->is_first = false;
        $this->is_last = false;
        $this->previous_id = null;
        $this->next_id = null;
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
        $query = CategoryOperation::with(['category_operation'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title_ru', 'like', $searchTerm)
                      ->orWhere('title_kk', 'like', $searchTerm)
                      ->orWhere('title_en', 'like', $searchTerm)
                      ->orWhere('value', 'like', $searchTerm);
                });
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $categories = $query->paginate(10);

        return view('livewire.admin.category-operation-management', [
            'categories' => $categories,
        ]);
    }
}

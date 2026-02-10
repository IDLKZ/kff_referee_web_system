<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\JudgeType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Judge Types')]
class JudgeTypeManagement extends Component
{
    use WithPagination;

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
    public ?int $editingId = null;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public string $value = '';
    public bool $is_active = true;

    // Delete target
    public ?int $deletingId = null;
    public string $deletingName = '';

    protected function rules(): array
    {
        $uniqueRule = 'unique:judge_types,value';
        if ($this->editingId) {
            $uniqueRule .= ',' . $this->editingId;
        }

        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255', $uniqueRule],
            'is_active' => ['boolean'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_TYPES_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_TYPES_UPDATE);
        $item = JudgeType::findOrFail($id);

        $this->editingId = $item->id;
        $this->title_ru = $item->title_ru;
        $this->title_kk = $item->title_kk ?? '';
        $this->title_en = $item->title_en ?? '';
        $this->value = $item->value;
        $this->is_active = $item->is_active;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::JUDGE_TYPES_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::JUDGE_TYPES_CREATE);
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
            JudgeType::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            JudgeType::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_TYPES_DELETE);
        $item = JudgeType::findOrFail($id);
        $this->deletingId = $item->id;
        $this->deletingName = $item->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::JUDGE_TYPES_DELETE);
        JudgeType::findOrFail($this->deletingId)->delete();

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
    }

    private function resetForm(): void
    {
        $this->editingId = null;
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
        $query = JudgeType::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title_ru', 'like', "%{$this->search}%")
                      ->orWhere('title_kk', 'like', "%{$this->search}%")
                      ->orWhere('title_en', 'like', "%{$this->search}%")
                      ->orWhere('value', 'like', "%{$this->search}%");
                });
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $items = $query->paginate(10);

        return view('livewire.admin.judge-type-management', [
            'items' => $items,
        ]);
    }
}

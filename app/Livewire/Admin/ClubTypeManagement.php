<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Http\Requests\ClubType\ClubTypeCreateRequest;
use App\Http\Requests\ClubType\ClubTypeUpdateRequest;
use App\Models\ClubType;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Club Types')]
class ClubTypeManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

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
    public ?int $file_id = null;
    public $image;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public string $value = '';
    public int $level = 1;
    public bool $is_active = true;

    // Image preview
    public ?string $temporaryImageUrl = null;
    public ?string $existingImageUrl = null;

    // Delete target
    public ?int $deletingId = null;
    public string $deletingInfo = '';
    public bool $deletingIsSoftDeleted = false;

    /**
     * Get validation rules from Form Request classes
     */
    protected function rules(): array
    {
        if ($this->isEditing) {
            $request = new ClubTypeUpdateRequest();
            $request->setEditingId($this->editingId);
            return $request->rules();
        }

        return (new ClubTypeCreateRequest())->rules();
    }

    /**
     * Get validation messages from Form Request classes
     */
    protected function messages(): array
    {
        if ($this->isEditing) {
            return (new ClubTypeUpdateRequest())->messages();
        }

        return (new ClubTypeCreateRequest())->messages();
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::CLUB_TYPES_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::CLUB_TYPES_UPDATE);
        $clubType = ClubType::withTrashed()->findOrFail($id);

        $this->editingId = $clubType->id;
        $this->file_id = $clubType->file_id;
        $this->title_ru = $clubType->title_ru;
        $this->title_kk = $clubType->title_kk ?? '';
        $this->title_en = $clubType->title_en ?? '';
        $this->value = $clubType->value;
        $this->level = $clubType->level;
        $this->is_active = $clubType->is_active ?? true;

        if ($clubType->file && Storage::disk('uploads')->exists($clubType->file->file_path)) {
            $this->existingImageUrl = Storage::disk('uploads')->url($clubType->file->file_path);
        }

        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::CLUB_TYPES_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::CLUB_TYPES_CREATE);
        }

        $this->validate();

        // Handle image upload
        if ($this->image instanceof \Illuminate\Http\UploadedFile) {
            $fileService = app(\App\Services\File\FileService::class);
            $fileService->setDisk('uploads');
            $file = $fileService->save(
                $this->image,
                'club-types',
                \App\Services\File\DTO\FileValidationOptions::images(maxSizeMB: 5)
            );
            $this->file_id = $file->id;
        }

        $data = [
            'file_id' => $this->file_id,
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'value' => $this->value,
            'level' => $this->level,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            ClubType::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            ClubType::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::CLUB_TYPES_DELETE);
        $clubType = ClubType::withTrashed()->find($id);

        $this->deletingId = $clubType->id;
        $this->deletingInfo = $clubType->title_ru;
        $this->deletingIsSoftDeleted = $clubType->trashed();
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::CLUB_TYPES_DELETE);

        $clubType = ClubType::withTrashed()->find($this->deletingId);

        if ($clubType->trashed()) {
            $clubType->forceDelete();
            toastr()->success(__('crud.deleted_success'));
        } else {
            $clubType->delete();
            toastr()->success(__('crud.deleted_success'));
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function restore(int $id): void
    {
        $this->checkPermission(PermissionConstants::CLUB_TYPES_DELETE);

        $clubType = ClubType::withTrashed()->findOrFail($id);
        $clubType->restore();

        toastr()->success(__('crud.restored_success'));
    }

    public function removeImage(): void
    {
        $this->image = null;
        $this->file_id = null;
        $this->temporaryImageUrl = null;
        $this->existingImageUrl = null;
    }

    public function updatedImage(): void
    {
        if ($this->image instanceof \Illuminate\Http\UploadedFile) {
            $this->temporaryImageUrl = $this->image->temporaryUrl();
        }
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
        $this->file_id = null;
        $this->image = null;
        $this->temporaryImageUrl = null;
        $this->existingImageUrl = null;
        $this->title_ru = '';
        $this->title_kk = '';
        $this->title_en = '';
        $this->value = '';
        $this->level = 1;
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
        $query = ClubType::with('file')
            ->when($this->search, function ($query) {
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

        $clubTypes = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.club-type-management', [
            'clubTypes' => $clubTypes,
        ]);
    }
}

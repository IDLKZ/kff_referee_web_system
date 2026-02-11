<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Http\Requests\Stadium\StadiumCreateRequest;
use App\Http\Requests\Stadium\StadiumUpdateRequest;
use App\Models\City;
use App\Models\Stadium;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Stadiums')]
class StadiumsManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Search & Filter
    public string $search = '';
    public ?int $filter_city_id = null;
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
    public ?int $city_id = null;
    public $image;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public ?string $description_ru = '';
    public ?string $description_kk = '';
    public ?string $description_en = '';
    public ?string $address_ru = '';
    public ?string $address_kk = '';
    public ?string $address_en = '';
    public ?string $built_date = '';
    public ?string $phone = '';
    public ?string $website = '';
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
            $request = new StadiumUpdateRequest();
            $request->setEditingId($this->editingId);
            return $request->rules();
        }

        return (new StadiumCreateRequest())->rules();
    }

    /**
     * Get validation messages from Form Request classes
     */
    protected function messages(): array
    {
        if ($this->isEditing) {
            return (new StadiumUpdateRequest())->messages();
        }

        return (new StadiumCreateRequest())->messages();
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::STADIUMS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::STADIUMS_UPDATE);
        $stadium = Stadium::with('file', 'city')->findOrFail($id);

        $this->editingId = $stadium->id;
        $this->file_id = $stadium->file_id;
        $this->city_id = $stadium->city_id;
        $this->title_ru = $stadium->title_ru;
        $this->title_kk = $stadium->title_kk ?? '';
        $this->title_en = $stadium->title_en ?? '';
        $this->description_ru = $stadium->description_ru ?? '';
        $this->description_kk = $stadium->description_kk ?? '';
        $this->description_en = $stadium->description_en ?? '';
        $this->address_ru = $stadium->address_ru ?? '';
        $this->address_kk = $stadium->address_kk ?? '';
        $this->address_en = $stadium->address_en ?? '';
        $this->built_date = $stadium->built_date?->format('Y-m-d') ?? '';
        $this->phone = $stadium->phone ?? '';
        $this->website = $stadium->website ?? '';
        $this->is_active = $stadium->is_active;

        if ($stadium->file && Storage::disk('uploads')->exists($stadium->file->file_path)) {
            $this->existingImageUrl = Storage::disk('uploads')->url($stadium->file->file_path);
        }

        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::STADIUMS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::STADIUMS_CREATE);
        }

        $this->validate();

        // Handle image upload
        if ($this->image instanceof \Illuminate\Http\UploadedFile) {
            $fileService = app(\App\Services\File\FileService::class);
            $fileService->setDisk('uploads');
            $file = $fileService->save(
                $this->image,
                'stadiums',
                \App\Services\File\DTO\FileValidationOptions::images(maxSizeMB: 5)
            );
            $this->file_id = $file->id;
        }

        $data = [
            'file_id' => $this->file_id,
            'city_id' => $this->city_id,
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'description_ru' => $this->description_ru ?: null,
            'description_kk' => $this->description_kk ?: null,
            'description_en' => $this->description_en ?: null,
            'address_ru' => $this->address_ru ?: null,
            'address_kk' => $this->address_kk ?: null,
            'address_en' => $this->address_en ?: null,
            'built_date' => $this->built_date ?: null,
            'phone' => $this->phone ?: null,
            'website' => $this->website ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            Stadium::findOrFail($this->editingId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            Stadium::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::STADIUMS_DELETE);
        $stadium = Stadium::findOrFail($id);

        $this->deletingId = $stadium->id;
        $this->deletingInfo = $stadium->title_ru;
        $this->deletingIsSoftDeleted = false;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::STADIUMS_DELETE);

        Stadium::findOrFail($this->deletingId)->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingId = null;
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
        $this->filter_city_id = null;
        $this->filter_is_active = null;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterCityId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterIsActive(): void
    {
        $this->resetPage();
    }

    public function getCityOptions(): array
    {
        return City::where('is_active', true)
            ->orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->isEditing = false;
        $this->file_id = null;
        $this->image = null;
        $this->temporaryImageUrl = null;
        $this->existingImageUrl = null;
        $this->city_id = null;
        $this->title_ru = '';
        $this->title_kk = '';
        $this->title_en = '';
        $this->description_ru = '';
        $this->description_kk = '';
        $this->description_en = '';
        $this->address_ru = '';
        $this->address_kk = '';
        $this->address_en = '';
        $this->built_date = '';
        $this->phone = '';
        $this->website = '';
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
        $query = Stadium::with(['file', 'city'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title_ru', 'like', "%{$this->search}%")
                      ->orWhere('title_kk', 'like', "%{$this->search}%")
                      ->orWhere('title_en', 'like', "%{$this->search}%")
                      ->orWhere('address_ru', 'like', "%{$this->search}%")
                      ->orWhere('address_kk', 'like', "%{$this->search}%")
                      ->orWhere('address_en', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filter_city_id, function ($query) {
                $query->where('city_id', $this->filter_city_id);
            })
            ->when($this->filter_is_active !== null, function ($query) {
                $query->where('is_active', $this->filter_is_active);
            });

        $stadiums = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.stadiums-management', [
            'stadiums' => $stadiums,
        ]);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Country;
use App\Models\Tournament;
use App\Services\File\FileService;
use App\Services\File\DTO\FileValidationOptions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Tournaments')]
class TournamentManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Search & Filter
    public string $search = '';
    public ?int $filter_country_id = null;
    public ?int $filter_sex = null;
    public ?bool $filter_is_active = null;

    // Sorting
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingTournamentId = null;
    public ?int $country_id = null;
    public $image;
    public ?int $image_id = null;
    public string $title_ru = '';
    public string $title_kk = '';
    public string $title_en = '';
    public string $short_title_ru = '';
    public string $short_title_kk = '';
    public string $short_title_en = '';
    public ?string $description_ru = '';
    public ?string $description_kk = '';
    public ?string $description_en = '';
    public string $value = '';
    public int $level = 1;
    public int $sex = 0;
    public bool $is_active = true;

    // Image preview
    public ?string $temporaryImageUrl = null;
    public ?string $existingImageUrl = null;

    // Delete target
    public ?int $deletingTournamentId = null;
    public string $deletingTournamentInfo = '';

    public function rules(): array
    {
        $uniqueRule = 'unique:tournaments,value';
        if ($this->editingTournamentId) {
            $uniqueRule .= ',' . $this->editingTournamentId;
        }

        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'short_title_ru' => ['required', 'string', 'max:255'],
            'short_title_kk' => ['required', 'string', 'max:255'],
            'short_title_en' => ['required', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_kk' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'value' => ['required', 'string', 'max:255', $uniqueRule],
            'country_id' => ['nullable', 'exists:countries,id'],
            'level' => ['required', 'integer', 'min:1'],
            'sex' => ['required', 'in:0,1,2'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCountryId(): void
    {
        $this->resetPage();
    }

    public function updatingFilterSex(): void
    {
        $this->resetPage();
    }

    public function updatingFilterIsActive(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::TOURNAMENTS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::TOURNAMENTS_UPDATE);
        $tournament = Tournament::with('file')->findOrFail($id);

        $this->editingTournamentId = $tournament->id;
        $this->isEditing = true;
        $this->country_id = $tournament->country_id;
        $this->image_id = $tournament->file_id;
        $this->title_ru = $tournament->title_ru;
        $this->title_kk = $tournament->title_kk ?? '';
        $this->title_en = $tournament->title_en ?? '';
        $this->short_title_ru = $tournament->short_title_ru ?? '';
        $this->short_title_kk = $tournament->short_title_kk ?? '';
        $this->short_title_en = $tournament->short_title_en ?? '';
        $this->description_ru = $tournament->description_ru ?? '';
        $this->description_kk = $tournament->description_kk ?? '';
        $this->description_en = $tournament->description_en ?? '';
        $this->value = $tournament->value;
        $this->level = $tournament->level;
        $this->sex = $tournament->sex;
        $this->is_active = $tournament->is_active;

        $this->existingImageUrl = null;
        if ($tournament->file && Storage::disk('uploads')->exists($tournament->file->file_path)) {
            $this->existingImageUrl = Storage::disk('uploads')->url($tournament->file->file_path);
        }

        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::TOURNAMENTS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::TOURNAMENTS_CREATE);
        }

        $this->validate();

        // Handle image upload
        if ($this->image instanceof UploadedFile) {
            $fileService = app(FileService::class);
            $fileService->setDisk('uploads');
            $file = $fileService->save(
                $this->image,
                'tournaments',
                FileValidationOptions::images(maxSizeMB: 5)
            );
            $this->image_id = $file->id;
        }

        $data = [
            'file_id' => $this->image_id,
            'country_id' => $this->country_id ?: null,
            'title_ru' => $this->title_ru,
            'title_kk' => $this->title_kk ?: null,
            'title_en' => $this->title_en ?: null,
            'short_title_ru' => $this->short_title_ru ?: '',
            'short_title_kk' => $this->short_title_kk ?: '',
            'short_title_en' => $this->short_title_en ?: '',
            'description_ru' => $this->description_ru ?: null,
            'description_kk' => $this->description_kk ?: null,
            'description_en' => $this->description_en ?: null,
            'value' => $this->value,
            'level' => $this->level,
            'sex' => $this->sex,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            Tournament::findOrFail($this->editingTournamentId)->update($data);
            toastr()->success(__('crud.updated_success'));
        } else {
            Tournament::create($data);
            toastr()->success(__('crud.created_success'));
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::TOURNAMENTS_DELETE);
        $tournament = Tournament::findOrFail($id);
        $this->deletingTournamentId = $tournament->id;
        $this->deletingTournamentInfo = $tournament->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::TOURNAMENTS_DELETE);
        Tournament::findOrFail($this->deletingTournamentId)->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingTournamentId = null;
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
        $this->filter_country_id = null;
        $this->filter_sex = null;
        $this->filter_is_active = null;
    }

    public function removeImage(): void
    {
        $this->image = null;
        $this->image_id = null;
        $this->temporaryImageUrl = null;
        $this->existingImageUrl = null;
    }

    public function updatedImage(): void
    {
        if ($this->image instanceof UploadedFile) {
            $this->temporaryImageUrl = $this->image->temporaryUrl();
        }
    }

    public function getCountryOptions(): array
    {
        return Country::where('is_active', true)
            ->orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    public function getSexOptions(): array
    {
        return [
            0 => __('crud.sex_not_specified'),
            1 => __('crud.sex_male'),
            2 => __('crud.sex_female'),
        ];
    }

    private function resetForm(): void
    {
        $this->editingTournamentId = null;
        $this->country_id = null;
        $this->image = null;
        $this->image_id = null;
        $this->temporaryImageUrl = null;
        $this->existingImageUrl = null;
        $this->title_ru = '';
        $this->title_kk = '';
        $this->title_en = '';
        $this->short_title_ru = '';
        $this->short_title_kk = '';
        $this->short_title_en = '';
        $this->description_ru = '';
        $this->description_kk = '';
        $this->description_en = '';
        $this->value = '';
        $this->level = 1;
        $this->sex = 0;
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
        $query = Tournament::with(['country', 'file'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title_ru', 'like', "%{$this->search}%")
                      ->orWhere('title_kk', 'like', "%{$this->search}%")
                      ->orWhere('title_en', 'like', "%{$this->search}%")
                      ->orWhere('value', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filter_country_id, function ($query) {
                $query->where('country_id', $this->filter_country_id);
            })
            ->when($this->filter_sex !== null, function ($query) {
                $query->where('sex', $this->filter_sex);
            })
            ->when($this->filter_is_active !== null, function ($query) {
                $query->where('is_active', $this->filter_is_active);
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $tournaments = $query->paginate(10);

        return view('livewire.admin.tournament-management', [
            'tournaments' => $tournaments,
        ]);
    }
}

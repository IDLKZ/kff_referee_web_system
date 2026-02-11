<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\City;
use App\Models\Club;
use App\Models\ClubType;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Clubs')]
class ClubManagement extends Component
{
    use WithPagination;

    // Search & Filters
    public string $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public ?int $filterCity = null;
    public ?int $filterType = null;
    public ?int $filterParent = null;
    public ?string $filterActive = null;

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingId = null;
    public ?int $file_id = null;
    public ?int $parent_id = null;
    public ?int $city_id = null;
    public ?int $type_id = null;
    public string $short_name_ru = '';
    public string $short_name_kk = '';
    public string $short_name_en = '';
    public string $full_name_ru = '';
    public string $full_name_kk = '';
    public string $full_name_en = '';
    public ?string $description_ru = null;
    public ?string $description_kk = null;
    public ?string $description_en = null;
    public ?string $bin = null;
    public ?string $foundation_date = null;
    public ?string $address_ru = null;
    public ?string $address_kk = null;
    public ?string $address_en = null;
    public ?string $phone = null;
    public ?string $website = null;
    public bool $is_active = true;

    // Image upload
    public $image;

    // Delete target
    public ?int $deletingClubId = null;
    public string $deletingClubName = '';

    // Services
    protected FileService $fileService;

    public function boot(): void
    {
        $this->fileService = app(FileService::class);
    }

    protected function rules(): array
    {
        return [
            'file_id' => ['nullable', 'exists:files,id'],
            'parent_id' => ['nullable', 'exists:clubs,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'type_id' => ['nullable', 'exists:club_types,id'],
            'short_name_ru' => ['required', 'string', 'max:255'],
            'short_name_kk' => ['required', 'string', 'max:255'],
            'short_name_en' => ['required', 'string', 'max:255'],
            'full_name_ru' => ['required', 'string', 'max:255'],
            'full_name_kk' => ['required', 'string', 'max:255'],
            'full_name_en' => ['required', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_kk' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'bin' => ['nullable', 'regex:/^[0-9]{12}$/', 'max:12'],
            'foundation_date' => ['nullable', 'date'],
            'address_ru' => ['nullable', 'string'],
            'address_kk' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    protected function messages(): array
    {
        return [
            'bin.regex' => __('crud.bin_format'),
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::CLUBS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::CLUBS_UPDATE);
        $club = Club::findOrFail($id);

        $this->editingId = $club->id;
        $this->file_id = $club->file_id;
        $this->parent_id = $club->parent_id;
        $this->city_id = $club->city_id;
        $this->type_id = $club->type_id;
        $this->short_name_ru = $club->short_name_ru;
        $this->short_name_kk = $club->short_name_kk;
        $this->short_name_en = $club->short_name_en;
        $this->full_name_ru = $club->full_name_ru;
        $this->full_name_kk = $club->full_name_kk;
        $this->full_name_en = $club->full_name_en;
        $this->description_ru = $club->description_ru;
        $this->description_kk = $club->description_kk;
        $this->description_en = $club->description_en;
        $this->bin = $club->bin;
        $this->foundation_date = $club->foundation_date?->format('Y-m-d');
        $this->address_ru = $club->address_ru;
        $this->address_kk = $club->address_kk;
        $this->address_en = $club->address_en;
        $this->phone = is_array($club->phone) ? implode(', ', $club->phone) : $club->phone;
        $this->website = $club->website;
        $this->is_active = $club->is_active;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::CLUBS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::CLUBS_CREATE);
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'parent_id' => $this->parent_id ?: null,
                'city_id' => $this->city_id ?: null,
                'type_id' => $this->type_id ?: null,
                'short_name_ru' => $this->short_name_ru,
                'short_name_kk' => $this->short_name_kk,
                'short_name_en' => $this->short_name_en,
                'full_name_ru' => $this->full_name_ru,
                'full_name_kk' => $this->full_name_kk,
                'full_name_en' => $this->full_name_en,
                'description_ru' => $this->description_ru ?: null,
                'description_kk' => $this->description_kk ?: null,
                'description_en' => $this->description_en ?: null,
                'bin' => $this->bin ?: null,
                'foundation_date' => $this->foundation_date ?: null,
                'address_ru' => $this->address_ru ?: null,
                'address_kk' => $this->address_kk ?: null,
                'address_en' => $this->address_en ?: null,
                'website' => $this->website ?: null,
                'is_active' => $this->is_active,
            ];

            // Handle image upload
            if ($this->image) {
                $file = $this->fileService->upload($this->image, 'clubs');
                $data['file_id'] = $file->id;
            } elseif ($this->file_id) {
                $data['file_id'] = $this->file_id;
            }

            // Handle phone (array)
            if ($this->phone) {
                $data['phone'] = array_map('trim', explode(',', $this->phone));
            } else {
                $data['phone'] = null;
            }

            if ($this->isEditing) {
                Club::findOrFail($this->editingId)->update($data);
                toastr()->success(__('crud.updated_success'));
            } else {
                Club::create($data);
                toastr()->success(__('crud.created_success'));
            }

            DB::commit();
            $this->showFormModal = false;
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->checkPermission(PermissionConstants::CLUBS_DELETE);
        $club = Club::withTrashed()->findOrFail($id);

        $this->deletingClubId = $club->id;
        $this->deletingClubName = $club->short_name_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::CLUBS_DELETE);
        $club = Club::withTrashed()->findOrFail($this->deletingClubId);

        if ($club->trashed()) {
            $club->forceDelete();
        } else {
            $club->delete();
        }

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingClubId = null;
    }

    public function restore(int $id): void
    {
        $this->checkPermission(PermissionConstants::CLUBS_DELETE);
        $club = Club::withTrashed()->findOrFail($id);
        $club->restore();

        toastr()->success(__('crud.restored_success'));
    }

    public function removeImage(): void
    {
        $this->image = null;
        $this->file_id = null;
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
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
        $this->filterCity = null;
        $this->filterType = null;
        $this->filterParent = null;
        $this->filterActive = null;
    }

    public function getCityOptions(): array
    {
        return City::orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    public function getClubTypeOptions(): array
    {
        return ClubType::orderBy('level')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    public function getParentClubOptions(): array
    {
        return Club::whereNull('parent_id')
            ->orderBy('short_name_ru')
            ->pluck('short_name_ru', 'id')
            ->toArray();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->file_id = null;
        $this->parent_id = null;
        $this->city_id = null;
        $this->type_id = null;
        $this->short_name_ru = '';
        $this->short_name_kk = '';
        $this->short_name_en = '';
        $this->full_name_ru = '';
        $this->full_name_kk = '';
        $this->full_name_en = '';
        $this->description_ru = null;
        $this->description_kk = null;
        $this->description_en = null;
        $this->bin = null;
        $this->foundation_date = null;
        $this->address_ru = null;
        $this->address_kk = null;
        $this->address_en = null;
        $this->phone = null;
        $this->website = null;
        $this->is_active = true;
        $this->image = null;
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
        $query = Club::with(['file', 'city', 'club_type', 'club'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('short_name_ru', 'like', $searchTerm)
                        ->orWhere('short_name_kk', 'like', $searchTerm)
                        ->orWhere('short_name_en', 'like', $searchTerm)
                        ->orWhere('full_name_ru', 'like', $searchTerm)
                        ->orWhere('full_name_kk', 'like', $searchTerm)
                        ->orWhere('full_name_en', 'like', $searchTerm)
                        ->orWhere('bin', 'like', $searchTerm);
                });
            })
            ->when($this->filterCity, function ($query) {
                $query->where('city_id', $this->filterCity);
            })
            ->when($this->filterType, function ($query) {
                $query->where('type_id', $this->filterType);
            })
            ->when($this->filterParent, function ($query) {
                $query->where('parent_id', $this->filterParent);
            })
            ->when($this->filterActive !== null, function ($query) {
                $query->where('is_active', $this->filterActive === 'true');
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $clubs = $query->paginate(10);

        return view('livewire.admin.club-management', [
            'clubs' => $clubs,
        ]);
    }
}

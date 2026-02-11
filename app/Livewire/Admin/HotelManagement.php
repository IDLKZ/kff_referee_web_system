<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\City;
use App\Models\Hotel;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Hotels')]
class HotelManagement extends Component
{
    use WithPagination;

    // Search & Filters
    public string $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public ?int $filterCity = null;
    public ?int $filterStar = null;
    public ?string $filterPartner = null;

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingId = null;
    public ?int $file_id = null;
    public ?int $city_id = null;
    public string $title_ru = '';
    public ?string $title_kk = null;
    public ?string $title_en = null;
    public ?string $description_ru = null;
    public ?string $description_kk = null;
    public ?string $description_en = null;
    public int $star = 0;
    public ?string $email = null;
    public ?string $address_ru = null;
    public ?string $address_kk = null;
    public ?string $address_en = null;
    public ?string $website = null;
    public ?float $lat = null;
    public ?float $lon = null;
    public bool $is_active = true;
    public bool $is_partner = false;

    // Image upload
    public $image;

    // Delete target
    public ?int $deletingHotelId = null;
    public string $deletingHotelName = '';

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
            'city_id' => ['nullable', 'exists:cities,id'],
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_kk' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'star' => ['required', 'integer', 'min:0', 'max:5'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_ru' => ['nullable', 'string'],
            'address_kk' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
            'is_active' => ['boolean'],
            'is_partner' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::HOTELS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::HOTELS_UPDATE);
        $hotel = Hotel::findOrFail($id);

        $this->editingId = $hotel->id;
        $this->file_id = $hotel->file_id;
        $this->city_id = $hotel->city_id;
        $this->title_ru = $hotel->title_ru;
        $this->title_kk = $hotel->title_kk;
        $this->title_en = $hotel->title_en;
        $this->description_ru = $hotel->description_ru;
        $this->description_kk = $hotel->description_kk;
        $this->description_en = $hotel->description_en;
        $this->star = $hotel->star;
        $this->email = $hotel->email;
        $this->address_ru = $hotel->address_ru;
        $this->address_kk = $hotel->address_kk;
        $this->address_en = $hotel->address_en;
        $this->website = $hotel->website;
        $this->lat = $hotel->lat;
        $this->lon = $hotel->lon;
        $this->is_active = $hotel->is_active;
        $this->is_partner = $hotel->is_partner;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::HOTELS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::HOTELS_CREATE);
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'city_id' => $this->city_id ?: null,
                'title_ru' => $this->title_ru,
                'title_kk' => $this->title_kk ?: null,
                'title_en' => $this->title_en ?: null,
                'description_ru' => $this->description_ru ?: null,
                'description_kk' => $this->description_kk ?: null,
                'description_en' => $this->description_en ?: null,
                'star' => $this->star,
                'email' => $this->email ?: null,
                'address_ru' => $this->address_ru ?: null,
                'address_kk' => $this->address_kk ?: null,
                'address_en' => $this->address_en ?: null,
                'website' => $this->website ?: null,
                'lat' => $this->lat ?: null,
                'lon' => $this->lon ?: null,
                'is_active' => $this->is_active,
                'is_partner' => $this->is_partner,
            ];

            // Handle image upload
            if ($this->image) {
                $file = $this->fileService->upload($this->image, 'hotels');
                $data['file_id'] = $file->id;
            } elseif ($this->file_id) {
                $data['file_id'] = $this->file_id;
            }

            if ($this->isEditing) {
                Hotel::findOrFail($this->editingId)->update($data);
                toastr()->success(__('crud.updated_success'));
            } else {
                Hotel::create($data);
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
        $this->checkPermission(PermissionConstants::HOTELS_DELETE);
        $hotel = Hotel::findOrFail($id);

        $this->deletingHotelId = $hotel->id;
        $this->deletingHotelName = $hotel->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::HOTELS_DELETE);
        Hotel::findOrFail($this->deletingHotelId)->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingHotelId = null;
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
        $this->filterStar = null;
        $this->filterPartner = null;
    }

    public function getCityOptions(): array
    {
        return City::orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->file_id = null;
        $this->city_id = null;
        $this->title_ru = '';
        $this->title_kk = null;
        $this->title_en = null;
        $this->description_ru = null;
        $this->description_kk = null;
        $this->description_en = null;
        $this->star = 0;
        $this->email = null;
        $this->address_ru = null;
        $this->address_kk = null;
        $this->address_en = null;
        $this->website = null;
        $this->lat = null;
        $this->lon = null;
        $this->is_active = true;
        $this->is_partner = false;
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
        $query = Hotel::with(['file', 'city'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title_ru', 'like', $searchTerm)
                      ->orWhere('title_kk', 'like', $searchTerm)
                      ->orWhere('title_en', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm);
                });
            })
            ->when($this->filterCity, function ($query) {
                $query->where('city_id', $this->filterCity);
            })
            ->when($this->filterStar, function ($query) {
                $query->where('star', $this->filterStar);
            })
            ->when($this->filterPartner !== null, function ($query) {
                $query->where('is_partner', $this->filterPartner === 'true');
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $hotels = $query->paginate(10);

        return view('livewire.admin.hotel-management', [
            'hotels' => $hotels,
        ]);
    }
}

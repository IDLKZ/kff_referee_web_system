<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Facility;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\RoomFacility;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Hotel Rooms')]
class HotelRoomManagement extends Component
{
    use WithPagination;

    // Search & Filters
    public string $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public ?int $filterHotel = null;
    public ?int $filterBedQuantity = null;
    public ?bool $filterHasAc = null;
    public ?bool $filterHasWifi = null;

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;
    public bool $isEditing = false;

    // Form fields
    public ?int $editingId = null;
    public ?int $hotel_id = null;
    public ?int $file_id = null;
    public string $title_ru = '';
    public ?string $title_kk = null;
    public ?string $title_en = null;
    public ?string $description_ru = null;
    public ?string $description_kk = null;
    public ?string $description_en = null;
    public int $bed_quantity = 1;
    public float $room_size = 0;
    public bool $air_conditioning = false;
    public bool $private_bathroom = false;
    public bool $tv = false;
    public bool $wifi = false;
    public bool $smoking_allowed = false;

    // Facilities
    public array $selectedFacilities = [];

    // Image upload
    public $image;

    // Delete target
    public ?int $deletingRoomId = null;
    public string $deletingRoomName = '';

    // Services
    protected FileService $fileService;

    public function boot(): void
    {
        $this->fileService = app(FileService::class);
    }

    protected function rules(): array
    {
        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'file_id' => ['nullable', 'exists:files,id'],
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_kk' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'bed_quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'room_size' => ['required', 'numeric', 'min:0', 'max:1000'],
            'air_conditioning' => ['boolean'],
            'private_bathroom' => ['boolean'],
            'tv' => ['boolean'],
            'wifi' => ['boolean'],
            'smoking_allowed' => ['boolean'],
            'selectedFacilities' => ['array'],
            'selectedFacilities.*' => ['integer', 'exists:facilities,id'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::HOTEL_ROOMS_CREATE);
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->checkPermission(PermissionConstants::HOTEL_ROOMS_UPDATE);
        $room = HotelRoom::findOrFail($id);

        $this->editingId = $room->id;
        $this->hotel_id = $room->hotel_id;
        $this->file_id = $room->file_id;
        $this->title_ru = $room->title_ru;
        $this->title_kk = $room->title_kk;
        $this->title_en = $room->title_en;
        $this->description_ru = $room->description_ru;
        $this->description_kk = $room->description_kk;
        $this->description_en = $room->description_en;
        $this->bed_quantity = $room->bed_quantity;
        $this->room_size = $room->room_size;
        $this->air_conditioning = $room->air_conditioning;
        $this->private_bathroom = $room->private_bathroom;
        $this->tv = $room->tv;
        $this->wifi = $room->wifi;
        $this->smoking_allowed = $room->smoking_allowed;

        // Load facilities
        $this->selectedFacilities = $room->room_facilities()->pluck('facility_id')->toArray();

        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->checkPermission(PermissionConstants::HOTEL_ROOMS_UPDATE);
        } else {
            $this->checkPermission(PermissionConstants::HOTEL_ROOMS_CREATE);
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'hotel_id' => $this->hotel_id,
                'title_ru' => $this->title_ru,
                'title_kk' => $this->title_kk ?: null,
                'title_en' => $this->title_en ?: null,
                'description_ru' => $this->description_ru ?: null,
                'description_kk' => $this->description_kk ?: null,
                'description_en' => $this->description_en ?: null,
                'bed_quantity' => $this->bed_quantity,
                'room_size' => $this->room_size,
                'air_conditioning' => $this->air_conditioning,
                'private_bathroom' => $this->private_bathroom,
                'tv' => $this->tv,
                'wifi' => $this->wifi,
                'smoking_allowed' => $this->smoking_allowed,
            ];

            // Handle image upload
            if ($this->image) {
                $file = $this->fileService->upload($this->image, 'hotel-rooms');
                $data['file_id'] = $file->id;
            } elseif ($this->file_id) {
                $data['file_id'] = $this->file_id;
            }

            if ($this->isEditing) {
                $room = HotelRoom::findOrFail($this->editingId);
                $room->update($data);

                // Update facilities
                RoomFacility::where('room_id', $room->id)->delete();
                foreach ($this->selectedFacilities as $facilityId) {
                    RoomFacility::create([
                        'room_id' => $room->id,
                        'facility_id' => $facilityId,
                    ]);
                }

                toastr()->success(__('crud.updated_success'));
            } else {
                $room = HotelRoom::create($data);

                // Add facilities
                foreach ($this->selectedFacilities as $facilityId) {
                    RoomFacility::create([
                        'room_id' => $room->id,
                        'facility_id' => $facilityId,
                    ]);
                }

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
        $this->checkPermission(PermissionConstants::HOTEL_ROOMS_DELETE);
        $room = HotelRoom::findOrFail($id);

        $this->deletingRoomId = $room->id;
        $this->deletingRoomName = $room->title_ru;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::HOTEL_ROOMS_DELETE);
        HotelRoom::findOrFail($this->deletingRoomId)->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingRoomId = null;
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
        $this->filterHotel = null;
        $this->filterBedQuantity = null;
        $this->filterHasAc = null;
        $this->filterHasWifi = null;
    }

    public function getHotelOptions(): array
    {
        return Hotel::orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    public function getFacilityOptions(): array
    {
        return Facility::orderBy('title_ru')
            ->get()
            ->map(function ($facility) {
                return [
                    'id' => $facility->id,
                    'title_ru' => $facility->title_ru,
                    'title_kk' => $facility->title_kk,
                    'title_en' => $facility->title_en,
                ];
            })
            ->toArray();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->hotel_id = null;
        $this->file_id = null;
        $this->title_ru = '';
        $this->title_kk = null;
        $this->title_en = null;
        $this->description_ru = null;
        $this->description_kk = null;
        $this->description_en = null;
        $this->bed_quantity = 1;
        $this->room_size = 0;
        $this->air_conditioning = false;
        $this->private_bathroom = false;
        $this->tv = false;
        $this->wifi = false;
        $this->smoking_allowed = false;
        $this->selectedFacilities = [];
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
        $query = HotelRoom::with(['file', 'hotel', 'room_facilities.facility'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title_ru', 'like', $searchTerm)
                      ->orWhere('title_kk', 'like', $searchTerm)
                      ->orWhere('title_en', 'like', $searchTerm);
                });
            })
            ->when($this->filterHotel, function ($query) {
                $query->where('hotel_id', $this->filterHotel);
            })
            ->when($this->filterBedQuantity, function ($query) {
                $query->where('bed_quantity', $this->filterBedQuantity);
            })
            ->when($this->filterHasAc !== null, function ($query) {
                $query->where('air_conditioning', $this->filterHasAc);
            })
            ->when($this->filterHasWifi !== null, function ($query) {
                $query->where('wifi', $this->filterHasWifi);
            });

        $query->orderBy($this->sortField, $this->sortDirection);

        $rooms = $query->paginate(10);

        return view('livewire.admin.hotel-room-management', [
            'rooms' => $rooms,
        ]);
    }
}

<?php

namespace App\Livewire\Admin;

use App\Constants\PermissionConstants;
use App\Models\Facility;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\RoomFacility;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layout')]
#[Title('Room Facilities')]
class RoomFacilityManagement extends Component
{
    use WithPagination;

    // Search & Filters
    public string $search = '';
    public string $sortField = 'room_id';
    public string $sortDirection = 'asc';
    public ?int $filterHotel = null;
    public ?int $filterFacility = null;

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public bool $showSearchModal = false;

    // Form fields
    public ?int $selectedHotel = null;
    public ?int $room_id = null;
    public ?int $facility_id = null;

    // Delete target
    public ?int $deletingRoomId = null;
    public ?int $deletingFacilityId = null;
    public string $deletingDescription = '';

    /**
     * Правила валидации для создания связи
     */
    protected function rules(): array
    {
        return [
            'room_id' => ['required', 'exists:hotel_rooms,id'],
            'facility_id' => ['required', 'exists:facilities,id'],
        ];
    }

    /**
     * Пользовательские сообщения валидации
     */
    protected function messages(): array
    {
        return [
            'room_id.required' => __('crud.select_room_required'),
            'facility_id.required' => __('crud.select_facility_required'),
        ];
    }

    // --- Сброс пагинации при изменении фильтров ---

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterHotel(): void
    {
        $this->resetPage();
    }

    public function updatingFilterFacility(): void
    {
        $this->resetPage();
    }

    // --- Модальное окно создания ---

    public function openCreateModal(): void
    {
        $this->checkPermission(PermissionConstants::ROOM_FACILITIES_CREATE);
        $this->resetForm();
        $this->showFormModal = true;
    }

    /**
     * При смене отеля в форме сбрасываем выбранный номер
     */
    public function updatedSelectedHotel(): void
    {
        $this->room_id = null;
    }

    /**
     * Создание связи номер–удобство (с проверкой дубликатов)
     */
    public function save(): void
    {
        $this->checkPermission(PermissionConstants::ROOM_FACILITIES_CREATE);
        $this->validate();

        // Проверка на дубликат
        $exists = RoomFacility::where('room_id', $this->room_id)
            ->where('facility_id', $this->facility_id)
            ->exists();

        if ($exists) {
            $this->addError('facility_id', __('crud.room_facility_exists'));
            return;
        }

        RoomFacility::create([
            'room_id' => $this->room_id,
            'facility_id' => $this->facility_id,
        ]);

        toastr()->success(__('crud.created_success'));
        $this->showFormModal = false;
        $this->resetForm();
    }

    // --- Удаление ---

    public function confirmDelete(int $roomId, int $facilityId): void
    {
        $this->checkPermission(PermissionConstants::ROOM_FACILITIES_DELETE);

        $roomFacility = RoomFacility::with(['hotel_room.hotel', 'facility'])
            ->where('room_id', $roomId)
            ->where('facility_id', $facilityId)
            ->firstOrFail();

        $this->deletingRoomId = $roomId;
        $this->deletingFacilityId = $facilityId;

        // Описание для подтверждения
        $roomName = $roomFacility->hotel_room->title_ru ?? '';
        $hotelName = $roomFacility->hotel_room->hotel->title_ru ?? '';
        $facilityName = $roomFacility->facility->title_ru ?? '';
        $this->deletingDescription = "{$hotelName} — {$roomName} — {$facilityName}";

        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->checkPermission(PermissionConstants::ROOM_FACILITIES_DELETE);

        RoomFacility::where('room_id', $this->deletingRoomId)
            ->where('facility_id', $this->deletingFacilityId)
            ->delete();

        toastr()->success(__('crud.deleted_success'));
        $this->showDeleteModal = false;
        $this->deletingRoomId = null;
        $this->deletingFacilityId = null;
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
        $this->filterHotel = null;
        $this->filterFacility = null;
        $this->resetPage();
    }

    // --- Опции для выпадающих списков ---

    public function getHotelOptions(): array
    {
        return Hotel::orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    public function getFacilityOptions(): array
    {
        return Facility::orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    /**
     * Получить номера по выбранному отелю (для формы создания)
     */
    public function getRoomOptions(): array
    {
        if (!$this->selectedHotel) {
            return [];
        }

        return HotelRoom::where('hotel_id', $this->selectedHotel)
            ->orderBy('title_ru')
            ->pluck('title_ru', 'id')
            ->toArray();
    }

    // --- Сервисные методы ---

    private function resetForm(): void
    {
        $this->selectedHotel = null;
        $this->room_id = null;
        $this->facility_id = null;
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
        $query = RoomFacility::with(['hotel_room.hotel', 'facility'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    // Поиск по названию номера
                    $q->whereHas('hotel_room', function ($rq) use ($searchTerm) {
                        $rq->where('title_ru', 'like', $searchTerm)
                           ->orWhere('title_kk', 'like', $searchTerm)
                           ->orWhere('title_en', 'like', $searchTerm)
                           // Поиск по названию отеля
                           ->orWhereHas('hotel', function ($hq) use ($searchTerm) {
                               $hq->where('title_ru', 'like', $searchTerm)
                                  ->orWhere('title_kk', 'like', $searchTerm)
                                  ->orWhere('title_en', 'like', $searchTerm);
                           });
                    })
                    // Поиск по названию удобства
                    ->orWhereHas('facility', function ($fq) use ($searchTerm) {
                        $fq->where('title_ru', 'like', $searchTerm)
                           ->orWhere('title_kk', 'like', $searchTerm)
                           ->orWhere('title_en', 'like', $searchTerm);
                    });
                });
            })
            // Фильтр по отелю
            ->when($this->filterHotel, function ($query) {
                $query->whereHas('hotel_room', function ($q) {
                    $q->where('hotel_id', $this->filterHotel);
                });
            })
            // Фильтр по удобству
            ->when($this->filterFacility, function ($query) {
                $query->where('facility_id', $this->filterFacility);
            });

        // Сортировка
        if ($this->sortField === 'hotel') {
            $query->join('hotel_rooms', 'room_facilities.room_id', '=', 'hotel_rooms.id')
                  ->join('hotels', 'hotel_rooms.hotel_id', '=', 'hotels.id')
                  ->orderBy('hotels.title_ru', $this->sortDirection)
                  ->select('room_facilities.*');
        } elseif ($this->sortField === 'room') {
            $query->join('hotel_rooms', 'room_facilities.room_id', '=', 'hotel_rooms.id')
                  ->orderBy('hotel_rooms.title_ru', $this->sortDirection)
                  ->select('room_facilities.*');
        } elseif ($this->sortField === 'facility') {
            $query->join('facilities', 'room_facilities.facility_id', '=', 'facilities.id')
                  ->orderBy('facilities.title_ru', $this->sortDirection)
                  ->select('room_facilities.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $roomFacilities = $query->paginate(10);

        return view('livewire.admin.room-facility-management', [
            'roomFacilities' => $roomFacilities,
        ]);
    }
}

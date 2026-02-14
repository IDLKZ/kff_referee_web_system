<?php

namespace App\Livewire\Kff;

use App\Constants\OperationConstants;
use App\Constants\RoleConstants;
use App\Models\City;
use App\Models\File;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\MatchJudge;
use App\Models\MatchLogist;
use App\Models\MatchModel;
use App\Models\Operation;
use App\Models\Trip;
use App\Models\TripDocument;
use App\Models\TripHotel;
use App\Models\TripMigration;
use App\Models\TransportType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('kff.layout')]
#[Title('KFF Trip Detail')]
class KffTripDetail extends Component
{
    use WithFileUploads;

    public int $matchId;
    public MatchModel $match;
    public array $judges = [];
    public bool $isReadOnly = false;
    public string $currentOperationValue = '';

    // Trip modal
    public bool $showTripModal = false;
    public ?int $tripMatchJudgeId = null;
    public ?int $tripId = null;
    public ?int $tripTransportTypeId = null;
    public ?int $tripDepartureCityId = null;
    public ?string $tripLogistInfo = null;

    // TripMigration modal
    public bool $showTripMigrationModal = false;
    public ?int $tripMigrationTripId = null;
    public ?int $tripMigrationId = null;
    public ?int $tripMigrationTransportTypeId = null;
    public ?int $tripMigrationDepartureCityId = null;
    public ?int $tripMigrationArrivalCityId = null;
    public ?string $tripMigrationFromDate = null;
    public ?string $tripMigrationToDate = null;
    public ?string $tripMigrationInfo = null;

    // TripHotel modal
    public bool $showTripHotelModal = false;
    public ?int $tripHotelTripId = null;
    public ?int $tripHotelId = null;
    public ?int $tripHotelHotelId = null;
    public ?int $tripHotelRoomId = null;
    public ?string $tripHotelFromDate = null;
    public ?string $tripHotelToDate = null;
    public ?string $tripHotelInfo = null;

    // TripDocument modal
    public bool $showTripDocumentModal = false;
    public ?int $tripDocumentTripId = null;
    public ?int $tripDocumentId = null;
    public ?int $tripDocumentFileId = null;
    public ?string $tripDocumentTitle = null;
    public ?string $tripDocumentInfo = null;
    public ?float $tripDocumentPrice = null;
    public ?float $tripDocumentQty = null;
    public ?float $tripDocumentTotalPrice = null;
    public $tripDocumentFileUpload = null;

    public function mount(int $matchId): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            403
        );

        $this->matchId = $matchId;
        $this->loadMatch();
        $this->checkAccess();
        $this->checkReadOnlyStatus();
        $this->loadJudges();
    }

    protected function loadMatch(): void
    {
        $this->match = MatchModel::with([
            'tournament',
            'season',
            'ownerClub',
            'guestClub',
            'city',
            'stadium',
            'operation',
            'judge_requirements.judge_type',
            'match_logists.user',
            'trips' => function ($query) {
                $query->with([
                    'transport_type',
                    'city',
                    'arrival_city',
                    'judge',
                    'trip_migrations' => function ($q) {
                        $q->with('transport_type', 'city', 'arrival_city');
                    },
                    'trip_hotels' => function ($q) {
                        $q->with('hotel', 'hotel_room');
                    },
                    'trip_documents' => function ($q) {
                        $q->with('file');
                    },
                    'operation',
                ]);
            },
        ])->findOrFail($this->matchId);

        $this->currentOperationValue = $this->match->operation->value ?? '';
    }

    protected function checkAccess(): void
    {
        // Check if match is in match_logists for current logist
        $hasAccess = MatchLogist::where('match_id', $this->matchId)
            ->where('logist_id', auth()->id())
            ->exists();

        abort_unless($hasAccess, 403);

        // Check if match is in correct operation
        $validOperations = [
            OperationConstants::SELECT_TRANSPORT_DEPARTURE,
            OperationConstants::TRIP_PROCESSING,
            OperationConstants::WAITING_FOR_PROTOCOL,
        ];

        abort_unless(in_array($this->currentOperationValue, $validOperations), 403);
    }

    protected function checkReadOnlyStatus(): void
    {
        $this->isReadOnly = $this->currentOperationValue === OperationConstants::WAITING_FOR_PROTOCOL;
    }

    protected function loadJudges(): void
    {
        // Get judges that have accepted invitations (final_status==1, judge_response==1, is_actual==true)
        $this->judges = MatchJudge::where('match_id', $this->matchId)
            ->where('final_status', 1)
            ->where('judge_response', 1)
            ->where('is_actual', true)
            ->with([
                'user',
                'judge_type',
                'trip' => function ($query) {
                    $query->with([
                        'transport_type',
                        'city',
                        'arrival_city',
                        'trip_migrations' => function ($q) {
                            $q->with('transport_type', 'city', 'arrival_city');
                        },
                        'trip_hotels' => function ($q) {
                            $q->with('hotel', 'hotel_room');
                        },
                        'trip_documents' => function ($q) {
                            $q->with('file');
                        },
                        'operation',
                    ]);
                },
            ])
            ->get()
            ->toArray();
    }

    public function isAllTripsReady(): bool
    {
        foreach ($this->judges as $judge) {
            if (empty($judge['trip'])) {
                return false;
            }
            if (!$this->isTripReady($judge['trip'])) {
                return false;
            }
        }
        return true;
    }

    public function isTripReady(?array $trip): bool
    {
        if (!$trip) {
            return false;
        }
        return !empty($trip['transport_type_id']) && !empty($trip['departure_city_id']);
    }

    public function transitionToTripProcessing(): void
    {
        abort_unless(!$this->isReadOnly, 403);
        abort_unless($this->isAllTripsReady(), 403);

        $operationId = Operation::where('value', OperationConstants::TRIP_PROCESSING)
            ->pluck('id')
            ->first();

        $this->match->update([
            'current_operation_id' => $operationId,
        ]);

        // Also update all trips to TRIP_PROCESSING operation if not already
        foreach ($this->judges as $judge) {
            if ($judge['trip']) {
                $trip = Trip::find($judge['trip']['id']);
                if ($trip && $trip->operation_id != $operationId) {
                    $trip->update(['operation_id' => $operationId]);
                }
            }
        }

        $this->loadMatch();
        $this->loadJudges();
        session()->flash('message', __('crud.operation_changed_success'));
    }

    public function transitionToWaitingForProtocol(): void
    {
        abort_unless(!$this->isReadOnly, 403);
        abort_unless($this->isAllTripsReady(), 403);

        $operationId = Operation::where('value', OperationConstants::WAITING_FOR_PROTOCOL)
            ->pluck('id')
            ->first();

        $this->match->update([
            'current_operation_id' => $operationId,
        ]);

        $this->loadMatch();
        $this->loadJudges();
        $this->checkReadOnlyStatus();
        session()->flash('message', __('crud.operation_changed_success'));
    }

    public function markTripReady(int $tripId): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $trip = Trip::findOrFail($tripId);

        // Verify this trip belongs to this match
        abort_unless($trip->match_id === $this->matchId, 403);

        $operationId = Operation::where('value', OperationConstants::TRIP_PROCESSING)
            ->pluck('id')
            ->first();

        $trip->update(['operation_id' => $operationId]);

        $this->loadMatch();
        $this->loadJudges();
        session()->flash('message', __('crud.trip_saved_success'));
    }

    // Trip modal methods
    public function openTripModal(int $matchJudgeId): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $this->tripMatchJudgeId = $matchJudgeId;

        // Find existing trip for this match_judge
        $judge = collect($this->judges)->firstWhere('id', $matchJudgeId);

        if ($judge && $judge['trip']) {
            $trip = $judge['trip'];
            $this->tripId = $trip['id'];
            $this->tripTransportTypeId = $trip['transport_type_id'];
            $this->tripDepartureCityId = $trip['departure_city_id'];
            $this->tripLogistInfo = $trip['info'];
        } else {
            $this->tripId = null;
            $this->tripTransportTypeId = null;
            $this->tripDepartureCityId = null;
            $this->tripLogistInfo = null;
        }

        $this->showTripModal = true;
    }

    public function closeTripModal(): void
    {
        $this->showTripModal = false;
        $this->tripMatchJudgeId = null;
        $this->tripId = null;
        $this->tripTransportTypeId = null;
        $this->tripDepartureCityId = null;
        $this->tripLogistInfo = null;
        $this->resetErrorBag();
    }

    public function saveTrip(): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $this->validate([
            'tripTransportTypeId' => 'required|exists:transport_types,id',
            'tripDepartureCityId' => 'required|exists:cities,id',
            'tripLogistInfo' => 'nullable|string|max:1000',
        ]);

        $judge = collect($this->judges)->firstWhere('id', $this->tripMatchJudgeId);
        abort_unless($judge, 404);

        $operationId = Operation::where('value', OperationConstants::SELECT_TRANSPORT_DEPARTURE)
            ->pluck('id')
            ->first();

        Trip::updateOrCreate(
            [
                'match_id' => $this->matchId,
                'judge_id' => $judge['judge_id'],
            ],
            [
                'operation_id' => $operationId,
                'transport_type_id' => $this->tripTransportTypeId,
                'departure_city_id' => $this->tripDepartureCityId,
                'arrival_city_id' => $this->match->city_id,
                'logist_id' => auth()->id(),
                'info' => $this->tripLogistInfo,
            ]
        );

        $this->closeTripModal();
        $this->loadJudges();
        session()->flash('message', __('crud.trip_saved_success'));
    }

    // TripMigration modal methods
    public function openTripMigrationModal(int $tripId): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $this->tripMigrationTripId = $tripId;
        $this->tripMigrationId = null;
        $this->tripMigrationTransportTypeId = null;
        $this->tripMigrationDepartureCityId = null;
        $this->tripMigrationArrivalCityId = null;
        $this->tripMigrationFromDate = null;
        $this->tripMigrationToDate = null;
        $this->tripMigrationInfo = null;

        $this->showTripMigrationModal = true;
    }

    public function openTripMigrationModalEdit(int $id): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $migration = TripMigration::findOrFail($id);

        // Verify this migration belongs to a trip for this match
        $trip = $migration->trip;
        abort_unless($trip->match_id === $this->matchId, 403);

        $this->tripMigrationTripId = $trip->id;
        $this->tripMigrationId = $id;
        $this->tripMigrationTransportTypeId = $migration->transport_type_id;
        $this->tripMigrationDepartureCityId = $migration->departure_city_id;
        $this->tripMigrationArrivalCityId = $migration->arrival_city_id;
        $this->tripMigrationFromDate = $migration->from_date?->format('Y-m-d\TH:i');
        $this->tripMigrationToDate = $migration->to_date?->format('Y-m-d\TH:i');
        $this->tripMigrationInfo = $migration->info;

        $this->showTripMigrationModal = true;
    }

    public function closeTripMigrationModal(): void
    {
        $this->showTripMigrationModal = false;
        $this->tripMigrationTripId = null;
        $this->tripMigrationId = null;
        $this->tripMigrationTransportTypeId = null;
        $this->tripMigrationDepartureCityId = null;
        $this->tripMigrationArrivalCityId = null;
        $this->tripMigrationFromDate = null;
        $this->tripMigrationToDate = null;
        $this->tripMigrationInfo = null;
        $this->resetErrorBag();
    }

    public function saveTripMigration(): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $this->validate([
            'tripMigrationTransportTypeId' => 'required|exists:transport_types,id',
            'tripMigrationDepartureCityId' => 'required|exists:cities,id',
            'tripMigrationArrivalCityId' => 'required|exists:cities,id',
            'tripMigrationFromDate' => 'required|date',
            'tripMigrationToDate' => 'required|date|after:tripMigrationFromDate',
            'tripMigrationInfo' => 'nullable|string|max:1000',
        ]);

        TripMigration::updateOrCreate(
            ['id' => $this->tripMigrationId],
            [
                'trip_id' => $this->tripMigrationTripId,
                'transport_type_id' => $this->tripMigrationTransportTypeId,
                'departure_city_id' => $this->tripMigrationDepartureCityId,
                'arrival_city_id' => $this->tripMigrationArrivalCityId,
                'from_date' => $this->tripMigrationFromDate,
                'to_date' => $this->tripMigrationToDate,
                'info' => $this->tripMigrationInfo,
                'logist_id' => auth()->id(),
            ]
        );

        $this->closeTripMigrationModal();
        $this->loadJudges();
        session()->flash('message', __('crud.migration_saved_success'));
    }

    public function deleteTripMigration(int $id): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $migration = TripMigration::findOrFail($id);

        // Verify this migration belongs to a trip for this match
        $trip = $migration->trip;
        abort_unless($trip->match_id === $this->matchId, 403);

        $migration->delete();

        $this->loadJudges();
        session()->flash('message', __('crud.deleted_success'));
    }

    // TripHotel modal methods
    public function openTripHotelModal(int $tripId): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $this->tripHotelTripId = $tripId;
        $this->tripHotelId = null;
        $this->tripHotelHotelId = null;
        $this->tripHotelRoomId = null;
        $this->tripHotelFromDate = null;
        $this->tripHotelToDate = null;
        $this->tripHotelInfo = null;

        $this->showTripHotelModal = true;
    }

    public function openTripHotelModalEdit(int $id): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $hotel = TripHotel::findOrFail($id);

        // Verify this hotel belongs to a trip for this match
        $trip = $hotel->trip;
        abort_unless($trip->match_id === $this->matchId, 403);

        $this->tripHotelTripId = $trip->id;
        $this->tripHotelId = $id;
        $this->tripHotelHotelId = $hotel->hotel_id;
        $this->tripHotelRoomId = $hotel->room_id;
        $this->tripHotelFromDate = $hotel->from_date?->format('Y-m-d');
        $this->tripHotelToDate = $hotel->to_date?->format('Y-m-d');
        $this->tripHotelInfo = $hotel->info;

        $this->showTripHotelModal = true;
    }

    public function closeTripHotelModal(): void
    {
        $this->showTripHotelModal = false;
        $this->tripHotelTripId = null;
        $this->tripHotelId = null;
        $this->tripHotelHotelId = null;
        $this->tripHotelRoomId = null;
        $this->tripHotelFromDate = null;
        $this->tripHotelToDate = null;
        $this->tripHotelInfo = null;
        $this->resetErrorBag();
    }

    public function saveTripHotel(): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $this->validate([
            'tripHotelHotelId' => 'required|exists:hotels,id',
            'tripHotelRoomId' => 'nullable|exists:hotel_rooms,id',
            'tripHotelFromDate' => 'required|date',
            'tripHotelToDate' => 'required|date|after_or_equal:tripHotelFromDate',
            'tripHotelInfo' => 'nullable|string|max:1000',
        ]);

        TripHotel::updateOrCreate(
            ['id' => $this->tripHotelId],
            [
                'trip_id' => $this->tripHotelTripId,
                'hotel_id' => $this->tripHotelHotelId,
                'room_id' => $this->tripHotelRoomId,
                'from_date' => $this->tripHotelFromDate,
                'to_date' => $this->tripHotelToDate,
                'info' => $this->tripHotelInfo,
                'logist_id' => auth()->id(),
            ]
        );

        $this->closeTripHotelModal();
        $this->loadJudges();
        session()->flash('message', __('crud.hotel_saved_success'));
    }

    public function deleteTripHotel(int $id): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $hotel = TripHotel::findOrFail($id);

        // Verify this hotel belongs to a trip for this match
        $trip = $hotel->trip;
        abort_unless($trip->match_id === $this->matchId, 403);

        $hotel->delete();

        $this->loadJudges();
        session()->flash('message', __('crud.deleted_success'));
    }

    // TripDocument modal methods
    public function openTripDocumentModal(int $tripId): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $this->tripDocumentTripId = $tripId;
        $this->tripDocumentId = null;
        $this->tripDocumentFileId = null;
        $this->tripDocumentTitle = null;
        $this->tripDocumentInfo = null;
        $this->tripDocumentPrice = null;
        $this->tripDocumentQty = null;
        $this->tripDocumentTotalPrice = null;
        $this->tripDocumentFileUpload = null;

        $this->showTripDocumentModal = true;
    }

    public function openTripDocumentModalEdit(int $id): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $document = TripDocument::findOrFail($id);

        // Verify this document belongs to a trip for this match
        $trip = $document->trip;
        abort_unless($trip->match_id === $this->matchId, 403);

        $this->tripDocumentTripId = $trip->id;
        $this->tripDocumentId = $id;
        $this->tripDocumentFileId = $document->file_id;
        $this->tripDocumentTitle = $document->title;
        $this->tripDocumentInfo = $document->info;
        $this->tripDocumentPrice = $document->price;
        $this->tripDocumentQty = $document->qty;
        $this->tripDocumentTotalPrice = $document->total_price;
        $this->tripDocumentFileUpload = null;

        $this->showTripDocumentModal = true;
    }

    public function closeTripDocumentModal(): void
    {
        $this->showTripDocumentModal = false;
        $this->tripDocumentTripId = null;
        $this->tripDocumentId = null;
        $this->tripDocumentFileId = null;
        $this->tripDocumentTitle = null;
        $this->tripDocumentInfo = null;
        $this->tripDocumentPrice = null;
        $this->tripDocumentQty = null;
        $this->tripDocumentTotalPrice = null;
        $this->tripDocumentFileUpload = null;
        $this->resetErrorBag();
    }

    public function saveTripDocument(): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $this->validate([
            'tripDocumentTitle' => 'required|string|max:255',
            'tripDocumentInfo' => 'nullable|string|max:1000',
            'tripDocumentPrice' => 'nullable|numeric|min:0',
            'tripDocumentQty' => 'nullable|numeric|min:0',
            'tripDocumentFileUpload' => 'nullable|file|max:10240', // 10MB
        ]);

        $fileId = $this->tripDocumentFileId;

        // Handle file upload
        if ($this->tripDocumentFileUpload) {
            $path = $this->tripDocumentFileUpload->store('trip_documents', 'public');
            $file = File::create([
                'filename' => basename($path),
                'file_path' => $path,
                'file_size_bytes' => $this->tripDocumentFileUpload->getSize(),
                'mime_type' => $this->tripDocumentFileUpload->getMimeType(),
            ]);
            $fileId = $file->id;
        }

        // Calculate total price if price and qty are provided
        $totalPrice = null;
        if ($this->tripDocumentPrice !== null && $this->tripDocumentQty !== null) {
            $totalPrice = $this->tripDocumentPrice * $this->tripDocumentQty;
        } elseif ($this->tripDocumentTotalPrice !== null) {
            $totalPrice = $this->tripDocumentTotalPrice;
        }

        TripDocument::updateOrCreate(
            ['id' => $this->tripDocumentId],
            [
                'trip_id' => $this->tripDocumentTripId,
                'file_id' => $fileId,
                'title' => $this->tripDocumentTitle,
                'info' => $this->tripDocumentInfo,
                'price' => $this->tripDocumentPrice,
                'qty' => $this->tripDocumentQty,
                'total_price' => $totalPrice,
                'is_active' => true,
                'logist_id' => auth()->id(),
            ]
        );

        $this->closeTripDocumentModal();
        $this->loadJudges();
        session()->flash('message', __('crud.document_saved_success'));
    }

    public function deleteTripDocument(int $id): void
    {
        abort_unless(!$this->isReadOnly, 403);

        $document = TripDocument::findOrFail($id);

        // Verify this document belongs to a trip for this match
        $trip = $document->trip;
        abort_unless($trip->match_id === $this->matchId, 403);

        $document->delete();

        $this->loadJudges();
        session()->flash('message', __('crud.deleted_success'));
    }

    public function getTransportTypesProperty()
    {
        return TransportType::where('is_active', true)->orderBy('title_ru')->get();
    }

    public function getCitiesProperty()
    {
        return City::orderBy('title_ru')->get();
    }

    public function getHotelsProperty()
    {
        return Hotel::where('is_active', true)->orderBy('title_ru')->get();
    }

    public function getHotelRoomsProperty()
    {
        if (!$this->tripHotelHotelId) {
            return collect();
        }
        return HotelRoom::where('hotel_id', $this->tripHotelHotelId)
            ->orderBy('title_ru')
            ->get();
    }

    public function updatedTripHotelHotelId(): void
    {
        // Reset room when hotel changes
        $this->tripHotelRoomId = null;
    }

    public function render()
    {
        return view('livewire.kff.kff-trip-detail');
    }
}

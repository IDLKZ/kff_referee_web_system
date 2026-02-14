<?php

namespace App\Livewire\Referee;

use App\Constants\RoleConstants;
use App\Models\Trip;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('referee.layout')]
#[Title('Trip Detail')]
class RefereeTripDetail extends Component
{
    public Trip $trip;

    public function mount(int $tripId): void
    {
        abort_unless(
            auth()->user()->role->value === RoleConstants::SOCCER_REFEREE,
            403
        );

        $this->trip = Trip::with([
            'transport_type',
            'city',
            'arrival_city',
            'operation',
            'match' => function ($query) {
                $query->with([
                    'tournament',
                    'season',
                    'ownerClub',
                    'guestClub',
                    'city',
                    'stadium',
                    'operation',
                    'judge_requirements.judge_type',
                ]);
            },
            'trip_migrations' => function ($q) {
                $q->with('transport_type', 'city', 'arrival_city');
            },
            'trip_hotels' => function ($q) {
                $q->with('hotel', 'hotel_room');
            },
            'trip_documents' => function ($q) {
                $q->with('file');
            },
        ])->findOrFail($tripId);

        abort_unless($this->trip->judge_id === auth()->id(), 403);
    }

    public function render()
    {
        return view('livewire.referee.referee-trip-detail');
    }
}

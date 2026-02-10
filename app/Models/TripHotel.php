<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TripHotel
 * 
 * @property int $id
 * @property int $trip_id
 * @property int $hotel_id
 * @property int|null $room_id
 * @property Carbon $from_date
 * @property Carbon $to_date
 * @property string|null $info
 * @property int|null $logist_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Hotel $hotel
 * @property User|null $user
 * @property HotelRoom|null $hotel_room
 * @property Trip $trip
 *
 * @package App\Models
 */
class TripHotel extends Model
{
	protected $table = 'trip_hotels';

	protected $casts = [
		'trip_id' => 'int',
		'hotel_id' => 'int',
		'room_id' => 'int',
		'from_date' => 'datetime',
		'to_date' => 'datetime',
		'logist_id' => 'int'
	];

	protected $fillable = [
		'trip_id',
		'hotel_id',
		'room_id',
		'from_date',
		'to_date',
		'info',
		'logist_id'
	];

	public function hotel()
	{
		return $this->belongsTo(Hotel::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'logist_id');
	}

	public function hotel_room()
	{
		return $this->belongsTo(HotelRoom::class, 'room_id');
	}

	public function trip()
	{
		return $this->belongsTo(Trip::class);
	}
}

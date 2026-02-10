<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RoomFacility
 * 
 * @property int $room_id
 * @property int $facility_id
 * 
 * @property Facility $facility
 * @property HotelRoom $hotel_room
 *
 * @package App\Models
 */
class RoomFacility extends Model
{
	protected $table = 'room_facilities';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'room_id' => 'int',
		'facility_id' => 'int'
	];

	public function facility()
	{
		return $this->belongsTo(Facility::class);
	}

	public function hotel_room()
	{
		return $this->belongsTo(HotelRoom::class, 'room_id');
	}
}

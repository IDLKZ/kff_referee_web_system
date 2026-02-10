<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HotelRoom
 * 
 * @property int $id
 * @property int $hotel_id
 * @property int|null $file_id
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property int $bed_quantity
 * @property float $room_size
 * @property bool $air_conditioning
 * @property bool $private_bathroom
 * @property bool $tv
 * @property bool $wifi
 * @property bool $smoking_allowed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property File|null $file
 * @property Hotel $hotel
 * @property Collection|RoomFacility[] $room_facilities
 * @property Collection|TripHotel[] $trip_hotels
 *
 * @package App\Models
 */
class HotelRoom extends Model
{
	protected $table = 'hotel_rooms';

	protected $casts = [
		'hotel_id' => 'int',
		'file_id' => 'int',
		'bed_quantity' => 'int',
		'room_size' => 'float',
		'air_conditioning' => 'bool',
		'private_bathroom' => 'bool',
		'tv' => 'bool',
		'wifi' => 'bool',
		'smoking_allowed' => 'bool'
	];

	protected $fillable = [
		'hotel_id',
		'file_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'bed_quantity',
		'room_size',
		'air_conditioning',
		'private_bathroom',
		'tv',
		'wifi',
		'smoking_allowed'
	];

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function hotel()
	{
		return $this->belongsTo(Hotel::class);
	}

	public function room_facilities()
	{
		return $this->hasMany(RoomFacility::class, 'room_id');
	}

	public function trip_hotels()
	{
		return $this->hasMany(TripHotel::class, 'room_id');
	}
}

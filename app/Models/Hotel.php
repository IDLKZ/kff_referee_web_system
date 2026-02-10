<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Hotel
 * 
 * @property int $id
 * @property int|null $file_id
 * @property int|null $city_id
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property int $star
 * @property string|null $email
 * @property string|null $address_ru
 * @property string|null $address_kk
 * @property string|null $address_en
 * @property string|null $website
 * @property float|null $lat
 * @property float|null $lon
 * @property bool $is_active
 * @property bool $is_partner
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property City|null $city
 * @property File|null $file
 * @property Collection|HotelRoom[] $hotel_rooms
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class Hotel extends Model
{
	protected $table = 'hotels';

	protected $casts = [
		'file_id' => 'int',
		'city_id' => 'int',
		'star' => 'int',
		'lat' => 'float',
		'lon' => 'float',
		'is_active' => 'bool',
		'is_partner' => 'bool'
	];

	protected $fillable = [
		'file_id',
		'city_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'star',
		'email',
		'address_ru',
		'address_kk',
		'address_en',
		'website',
		'lat',
		'lon',
		'is_active',
		'is_partner'
	];

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function hotel_rooms()
	{
		return $this->hasMany(HotelRoom::class);
	}

	public function trips()
	{
		return $this->belongsToMany(Trip::class, 'trip_hotels')
					->withPivot('id', 'room_id', 'from_date', 'to_date', 'info', 'logist_id')
					->withTimestamps();
	}
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TripMigration
 * 
 * @property int $id
 * @property int $trip_id
 * @property int $transport_type_id
 * @property int $departure_city_id
 * @property int $arrival_city_id
 * @property Carbon $from_date
 * @property Carbon $to_date
 * @property string|null $info
 * @property int|null $logist_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property City $city
 * @property City $arrival_city
 * @property User|null $user
 * @property TransportType $transport_type
 * @property Trip $trip
 *
 * @package App\Models
 */
class TripMigration extends Model
{
	protected $table = 'trip_migrations';

	protected $casts = [
		'trip_id' => 'int',
		'transport_type_id' => 'int',
		'departure_city_id' => 'int',
		'arrival_city_id' => 'int',
		'from_date' => 'datetime',
		'to_date' => 'datetime',
		'logist_id' => 'int'
	];

	protected $fillable = [
		'trip_id',
		'transport_type_id',
		'departure_city_id',
		'arrival_city_id',
		'from_date',
		'to_date',
		'info',
		'logist_id'
	];

	public function city()
	{
		return $this->belongsTo(City::class, 'departure_city_id');
	}

	public function arrival_city()
	{
		return $this->belongsTo(City::class, 'arrival_city_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'logist_id');
	}

	public function transport_type()
	{
		return $this->belongsTo(TransportType::class);
	}

	public function trip()
	{
		return $this->belongsTo(Trip::class);
	}
}

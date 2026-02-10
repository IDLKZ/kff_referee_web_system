<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Trip
 *
 * @property int $id
 * @property int $match_id
 * @property int|null $departure_city_id
 * @property int|null $arrival_city_id
 * @property string|null $name
 * @property Carbon|null $departure_date
 * @property Carbon|null $return_date
 * @property int $transport_type_id
 * @property int|null $judge_id
 * @property int|null $logist_id
 * @property string|null $info
 * @property string|null $judge_comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property City|null $city
 * @property User|null $user
 * @property Match $match
 * @property TransportType $transport_type
 * @property Collection|TripDocument[] $trip_documents
 * @property Collection|Hotel[] $hotels
 * @property Collection|TripMigration[] $trip_migrations
 *
 * @package App\Models
 */
class Trip extends Model
{
	use SoftDeletes;
	protected $table = 'trips';

	protected $casts = [
		'match_id' => 'int',
		'departure_city_id' => 'int',
		'arrival_city_id' => 'int',
		'departure_date' => 'datetime',
		'return_date' => 'datetime',
		'transport_type_id' => 'int',
		'judge_id' => 'int',
		'logist_id' => 'int'
	];

	protected $fillable = [
		'match_id',
		'departure_city_id',
		'arrival_city_id',
		'name',
		'departure_date',
		'return_date',
		'transport_type_id',
		'judge_id',
		'logist_id',
		'info',
		'judge_comment'
	];

	public function city()
	{
		return $this->belongsTo(City::class, 'departure_city_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'logist_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}

	public function transport_type()
	{
		return $this->belongsTo(TransportType::class);
	}

	public function trip_documents()
	{
		return $this->hasMany(TripDocument::class);
	}

	public function hotels()
	{
		return $this->belongsToMany(Hotel::class, 'trip_hotels')
					->withPivot('id', 'room_id', 'from_date', 'to_date', 'info', 'logist_id')
					->withTimestamps();
	}

	public function trip_migrations()
	{
		return $this->hasMany(TripMigration::class);
	}
}

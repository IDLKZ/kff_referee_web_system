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
 * Class City
 *
 * @property int $id
 * @property int|null $country_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $value
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Country|null $country
 * @property Collection|Club[] $clubs
 * @property Collection|Hotel[] $hotels
 * @property Collection|JudgeCity[] $judge_cities
 * @property Collection|Match[] $matches
 * @property Collection|Stadium[] $stadiums
 * @property Collection|TripMigration[] $trip_migrations
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class City extends Model
{
	use SoftDeletes;
	protected $table = 'cities';

	protected $casts = [
		'country_id' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'country_id',
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'is_active'
	];

	public function country()
	{
		return $this->belongsTo(Country::class);
	}

	public function clubs()
	{
		return $this->hasMany(Club::class);
	}

	public function hotels()
	{
		return $this->hasMany(Hotel::class);
	}

	public function judge_cities()
	{
		return $this->hasMany(JudgeCity::class);
	}

	public function matches()
	{
		return $this->hasMany(MatchModel::class);
	}

	public function stadiums()
	{
		return $this->hasMany(Stadium::class);
	}

	public function trip_migrations()
	{
		return $this->hasMany(TripMigration::class, 'departure_city_id');
	}

	public function trips()
	{
		return $this->hasMany(Trip::class, 'departure_city_id');
	}
}

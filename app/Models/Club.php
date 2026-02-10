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
 * Class Club
 *
 * @property int $id
 * @property int|null $file_id
 * @property int|null $parent_id
 * @property int|null $city_id
 * @property int|null $type_id
 * @property string $short_name_ru
 * @property string $short_name_kk
 * @property string $short_name_en
 * @property string $full_name_ru
 * @property string $full_name_kk
 * @property string $full_name_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string|null $bin
 * @property Carbon|null $foundation_date
 * @property string|null $address_ru
 * @property string|null $address_kk
 * @property string|null $address_en
 * @property array|null $phone
 * @property string|null $website
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property City|null $city
 * @property File|null $file
 * @property Club|null $club
 * @property ClubType|null $club_type
 * @property Collection|Stadium[] $stadiums
 * @property Collection|Club[] $clubs
 * @property Collection|Match[] $matches
 *
 * @package App\Models
 */
class Club extends Model
{
	use SoftDeletes;
	protected $table = 'clubs';

	protected $casts = [
		'file_id' => 'int',
		'parent_id' => 'int',
		'city_id' => 'int',
		'type_id' => 'int',
		'foundation_date' => 'datetime',
		'phone' => 'json',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'file_id',
		'parent_id',
		'city_id',
		'type_id',
		'short_name_ru',
		'short_name_kk',
		'short_name_en',
		'full_name_ru',
		'full_name_kk',
		'full_name_en',
		'description_ru',
		'description_kk',
		'description_en',
		'bin',
		'foundation_date',
		'address_ru',
		'address_kk',
		'address_en',
		'phone',
		'website',
		'is_active'
	];

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function club()
	{
		return $this->belongsTo(Club::class, 'parent_id');
	}

	public function club_type()
	{
		return $this->belongsTo(ClubType::class, 'type_id');
	}

	public function stadiums()
	{
		return $this->belongsToMany(Stadium::class, 'club_stadiums');
	}

	public function clubs()
	{
		return $this->hasMany(Club::class, 'parent_id');
	}

	public function matches()
	{
		return $this->hasMany(MatchModel::class, 'winner_id');
	}
}

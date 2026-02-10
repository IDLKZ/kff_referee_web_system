<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Stadium
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
 * @property string|null $address_ru
 * @property string|null $address_kk
 * @property string|null $address_en
 * @property Carbon|null $built_date
 * @property string|null $phone
 * @property string|null $website
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property City|null $city
 * @property File|null $file
 * @property Collection|Club[] $clubs
 * @property Collection|Match[] $matches
 *
 * @package App\Models
 */
class Stadium extends Model
{
	protected $table = 'stadiums';

	protected $casts = [
		'file_id' => 'int',
		'city_id' => 'int',
		'built_date' => 'datetime',
		'is_active' => 'bool'
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
		'address_ru',
		'address_kk',
		'address_en',
		'built_date',
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

	public function clubs()
	{
		return $this->belongsToMany(Club::class, 'club_stadiums');
	}

	public function matches()
	{
		return $this->hasMany(MatchModel::class);
	}
}

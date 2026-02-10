<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tournament
 *
 * @property int $id
 * @property int|null $file_id
 * @property int|null $country_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $short_title_ru
 * @property string $short_title_kk
 * @property string $short_title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property int $level
 * @property int $sex
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Country|null $country
 * @property File|null $file
 * @property Collection|MatchProtocolRequirement[] $match_protocol_requirements
 * @property Collection|Match[] $matches
 *
 * @package App\Models
 */
class Tournament extends Model
{
	protected $table = 'tournaments';

	protected $casts = [
		'file_id' => 'int',
		'country_id' => 'int',
		'level' => 'int',
		'sex' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'file_id',
		'country_id',
		'title_ru',
		'title_kk',
		'title_en',
		'short_title_ru',
		'short_title_kk',
		'short_title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'level',
		'sex',
		'is_active'
	];

	public function country()
	{
		return $this->belongsTo(Country::class);
	}

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function match_protocol_requirements()
	{
		return $this->hasMany(MatchProtocolRequirement::class);
	}

	public function matches()
	{
		return $this->hasMany(MatchModel::class);
	}
}

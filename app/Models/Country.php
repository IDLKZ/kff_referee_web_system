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
 * Class Country
 * 
 * @property int $id
 * @property int|null $image_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $value
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property File|null $file
 * @property Collection|City[] $cities
 * @property Collection|Tournament[] $tournaments
 *
 * @package App\Models
 */
class Country extends Model
{
	use SoftDeletes;
	protected $table = 'countries';

	protected $casts = [
		'image_id' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'image_id',
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'is_active'
	];

	public function file()
	{
		return $this->belongsTo(File::class, 'image_id');
	}

	public function cities()
	{
		return $this->hasMany(City::class);
	}

	public function tournaments()
	{
		return $this->hasMany(Tournament::class);
	}
}

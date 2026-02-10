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
 * Class ClubType
 * 
 * @property int $id
 * @property int|null $file_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $value
 * @property int $level
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property File|null $file
 * @property Collection|Club[] $clubs
 *
 * @package App\Models
 */
class ClubType extends Model
{
	use SoftDeletes;
	protected $table = 'club_types';

	protected $casts = [
		'file_id' => 'int',
		'level' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'file_id',
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'level',
		'is_active'
	];

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function clubs()
	{
		return $this->hasMany(Club::class, 'type_id');
	}
}

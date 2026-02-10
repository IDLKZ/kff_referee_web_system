<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Season
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $value
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Match[] $matches
 *
 * @package App\Models
 */
class Season extends Model
{
	protected $table = 'seasons';

	protected $casts = [
		'start_at' => 'datetime',
		'end_at' => 'datetime'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'start_at',
		'end_at'
	];

	public function matches()
	{
		return $this->hasMany(MatchModel::class);
	}
}

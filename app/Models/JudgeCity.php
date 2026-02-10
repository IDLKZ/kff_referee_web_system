<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JudgeCity
 *
 * @property int $id
 * @property int $user_id
 * @property int $city_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property City $city
 * @property User $user
 *
 * @package App\Models
 */
class JudgeCity extends Model
{
	use SoftDeletes;

	protected $table = 'judge_cities';

	protected $fillable = [
		'user_id',
		'city_id',
	];

	protected $casts = [
		'user_id' => 'int',
		'city_id' => 'int',
	];

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

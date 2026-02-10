<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JudgeCity
 * 
 * @property int $user_id
 * @property int $city_id
 * 
 * @property City $city
 * @property User $user
 *
 * @package App\Models
 */
class JudgeCity extends Model
{
	protected $table = 'judge_cities';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'city_id' => 'int'
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

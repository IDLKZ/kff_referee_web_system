<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchLogist
 *
 * @property int $match_id
 * @property int $logist_id
 *
 * @property User $user
 * @property Match $match
 *
 * @package App\Models
 */
class MatchLogist extends Model
{
	protected $table = 'match_logists';
	public $timestamps = false;

	protected $casts = [
		'match_id' => 'int',
		'logist_id' => 'int'
	];

	protected $fillable = [
		'match_id',
		'logist_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'logist_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}
}

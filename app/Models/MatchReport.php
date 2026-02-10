<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchReport
 * 
 * @property int $id
 * @property int $match_judge_id
 * @property bool $is_finished
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property MatchJudge $match_judge
 *
 * @package App\Models
 */
class MatchReport extends Model
{
	protected $table = 'match_reports';

	protected $casts = [
		'match_judge_id' => 'int',
		'is_finished' => 'bool'
	];

	protected $fillable = [
		'match_judge_id',
		'is_finished'
	];

	public function match_judge()
	{
		return $this->belongsTo(MatchJudge::class);
	}
}

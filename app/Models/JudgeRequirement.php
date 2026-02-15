<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JudgeRequirement
 *
 * @property int $id
 * @property int $match_id
 * @property int $judge_type_id
 * @property int $qty
 * @property bool $is_required
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property JudgeType $judge_type
 * @property Match $match
 *
 * @package App\Models
 */
class JudgeRequirement extends Model
{
	protected $table = 'judge_requirements';

	protected $casts = [
		'match_id' => 'int',
		'judge_type_id' => 'int',
		'qty' => 'int',
		'is_required' => 'bool'
	];

	protected $fillable = [
		'match_id',
		'judge_type_id',
		'qty',
		'is_required'
	];

	public function judge_type()
	{
		return $this->belongsTo(JudgeType::class);
	}

	public function judgeType()
	{
		return $this->belongsTo(JudgeType::class, 'judge_type_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}
}

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
 * Class MatchJudge
 *
 * @property int $id
 * @property int $match_id
 * @property int $type_id
 * @property int $judge_id
 * @property string|null $request_comment
 * @property int $judge_response
 * @property string|null $judge_comment
 * @property int $final_status
 * @property string|null $final_comment
 * @property bool|null $is_actual
 * @property int|null $created_by_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property User $user
 * @property Match $match
 * @property JudgeType $judge_type
 * @property Collection|MatchReportDocument[] $match_report_documents
 * @property Collection|MatchReport[] $match_reports
 *
 * @package App\Models
 */
class MatchJudge extends Model
{
	use SoftDeletes;
	protected $table = 'match_judges';

	protected $casts = [
		'match_id' => 'int',
		'type_id' => 'int',
		'judge_id' => 'int',
		'judge_response' => 'int',
		'final_status' => 'int',
		'is_actual' => 'bool',
		'created_by_id' => 'int'
	];

	protected $fillable = [
		'match_id',
		'type_id',
		'judge_id',
		'request_comment',
		'judge_response',
		'judge_comment',
		'final_status',
		'final_comment',
		'is_actual',
		'created_by_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}

	public function judge_type()
	{
		return $this->belongsTo(JudgeType::class, 'type_id');
	}

	public function match_report_documents()
	{
		return $this->hasMany(MatchReportDocument::class);
	}

	public function match_reports()
	{
		return $this->hasMany(MatchReport::class);
	}
}

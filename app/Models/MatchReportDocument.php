<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchReportDocument
 *
 * @property int $id
 * @property int $file_id
 * @property int $match_judge_id
 * @property int $match_id
 * @property int $requirement_id
 * @property int $judge_id
 * @property string|null $comment
 * @property string|null $final_comment
 * @property bool $is_accepted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property File $file
 * @property User $user
 * @property Match $match
 * @property MatchJudge $match_judge
 * @property MatchProtocolRequirement $match_protocol_requirement
 *
 * @package App\Models
 */
class MatchReportDocument extends Model
{
	protected $table = 'match_report_documents';

	protected $casts = [
		'file_id' => 'int',
		'match_judge_id' => 'int',
		'match_id' => 'int',
		'requirement_id' => 'int',
		'judge_id' => 'int',
		'is_accepted' => 'bool'
	];

	protected $fillable = [
		'file_id',
		'match_judge_id',
		'match_id',
		'requirement_id',
		'judge_id',
		'comment',
		'final_comment',
		'is_accepted'
	];

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}

	public function match_judge()
	{
		return $this->belongsTo(MatchJudge::class);
	}

	public function match_protocol_requirement()
	{
		return $this->belongsTo(MatchProtocolRequirement::class, 'requirement_id');
	}
}

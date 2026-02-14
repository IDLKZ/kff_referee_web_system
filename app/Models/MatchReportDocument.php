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
 * @property int $match_report_id
 * @property int $file_id
 * @property int $match_id
 * @property int $requirement_id
 * @property int $judge_id
 * @property string|null $comment
 * @property string|null $final_comment
 * @property bool $is_accepted
 * @property int|null $checked_by_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property MatchReport $match_report
 * @property File $file
 * @property MatchModel $match
 * @property User $user
 * @property MatchProtocolRequirement $match_protocol_requirement
 * @property User|null $checked_by
 *
 * @package App\Models
 */
class MatchReportDocument extends Model
{
	protected $table = 'match_report_documents';

	protected $casts = [
		'match_report_id' => 'int',
		'file_id' => 'int',
		'match_id' => 'int',
		'requirement_id' => 'int',
		'judge_id' => 'int',
		'is_accepted' => 'bool',
		'checked_by_id' => 'int'
	];

	protected $fillable = [
		'match_report_id',
		'file_id',
		'match_id',
		'requirement_id',
		'judge_id',
		'comment',
		'final_comment',
		'is_accepted',
		'checked_by_id'
	];

	public function match_report()
	{
		return $this->belongsTo(MatchReport::class);
	}

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}

	public function match_protocol_requirement()
	{
		return $this->belongsTo(MatchProtocolRequirement::class, 'requirement_id');
	}

	public function checked_by()
	{
		return $this->belongsTo(User::class, 'checked_by_id');
	}
}

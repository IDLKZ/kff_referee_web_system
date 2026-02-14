<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchReport
 *
 * @property int $id
 * @property int $match_id
 * @property int|null $judge_id
 * @property bool $is_finished
 * @property bool|null $is_accepted
 * @property string|null $final_comment
 * @property int|null $checked_by_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property MatchModel $match
 * @property User|null $judge
 * @property User|null $checked_by
 * @property Collection|MatchReportDocument[] $match_report_documents
 *
 * @package App\Models
 */
class MatchReport extends Model
{
	protected $table = 'match_reports';

	protected $casts = [
		'match_id' => 'int',
		'judge_id' => 'int',
		'is_finished' => 'bool',
		'is_accepted' => 'bool',
		'checked_by_id' => 'int'
	];

	protected $fillable = [
		'match_id',
		'judge_id',
		'is_finished',
		'is_accepted',
		'final_comment',
		'checked_by_id'
	];

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}

	public function judge()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}

	public function checked_by()
	{
		return $this->belongsTo(User::class, 'checked_by_id');
	}

	public function match_report_documents()
	{
		return $this->hasMany(MatchReportDocument::class);
	}
}

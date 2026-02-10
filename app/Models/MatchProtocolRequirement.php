<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchProtocolRequirement
 *
 * @property int $id
 * @property int $tournament_id
 * @property int|null $match_id
 * @property int $judge_type_id
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string $info_ru
 * @property string|null $info_kk
 * @property string|null $info_en
 * @property bool $is_required
 * @property array $extensions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property JudgeType $judge_type
 * @property Match|null $match
 * @property Tournament $tournament
 * @property Collection|MatchReportDocument[] $match_report_documents
 *
 * @package App\Models
 */
class MatchProtocolRequirement extends Model
{
	protected $table = 'match_protocol_requirements';

	protected $casts = [
		'tournament_id' => 'int',
		'match_id' => 'int',
		'judge_type_id' => 'int',
		'is_required' => 'bool',
		'extensions' => 'json'
	];

	protected $fillable = [
		'tournament_id',
		'match_id',
		'judge_type_id',
		'title_ru',
		'title_kk',
		'title_en',
		'info_ru',
		'info_kk',
		'info_en',
		'is_required',
		'extensions'
	];

	public function judge_type()
	{
		return $this->belongsTo(JudgeType::class);
	}

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}

	public function tournament()
	{
		return $this->belongsTo(Tournament::class);
	}

	public function match_report_documents()
	{
		return $this->hasMany(MatchReportDocument::class, 'requirement_id');
	}
}

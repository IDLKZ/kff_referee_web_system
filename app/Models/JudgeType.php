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
 * Class JudgeType
 * 
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $value
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|JudgeRequirement[] $judge_requirements
 * @property Collection|MatchJudge[] $match_judges
 * @property Collection|MatchProtocolRequirement[] $match_protocol_requirements
 *
 * @package App\Models
 */
class JudgeType extends Model
{
	use SoftDeletes;
	protected $table = 'judge_types';

	protected $casts = [
		'is_active' => 'bool'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'is_active'
	];

	public function judge_requirements()
	{
		return $this->hasMany(JudgeRequirement::class);
	}

	public function match_judges()
	{
		return $this->hasMany(MatchJudge::class, 'type_id');
	}

	public function match_protocol_requirements()
	{
		return $this->hasMany(MatchProtocolRequirement::class);
	}
}

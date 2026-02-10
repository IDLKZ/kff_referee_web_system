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
 * Class Match
 *
 * @property int $id
 * @property int $tournament_id
 * @property int $season_id
 * @property int $stadium_id
 * @property int $current_operation_id
 * @property int $city_id
 * @property int $owner_club_id
 * @property int $guest_club_id
 * @property int|null $winner_id
 * @property int|null $owner_point
 * @property int|null $guest_point
 * @property int|null $round
 * @property Carbon $start_at
 * @property Carbon|null $end_at
 * @property bool $is_active
 * @property bool $is_finished
 * @property bool $is_canceled
 * @property string|null $cancel_reason
 * @property array|null $info
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property City $city
 * @property Operation $operation
 * @property Club $ownerClub
 * @property Club $guestClub
 * @property Club|null $winner
 * @property Season $season
 * @property Stadium $stadium
 * @property Tournament $tournament
 * @property Collection|JudgeRequirement[] $judge_requirements
 * @property Collection|MatchJudge[] $match_judges
 * @property Collection|MatchLogist[] $match_logists
 * @property Collection|Operation[] $operations
 * @property Collection|MatchProtocolRequirement[] $match_protocol_requirements
 * @property Collection|MatchReportDocument[] $match_report_documents
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class MatchModel extends Model
{
	use SoftDeletes;
	protected $table = 'matches';

	protected $casts = [
		'tournament_id' => 'int',
		'season_id' => 'int',
		'stadium_id' => 'int',
		'current_operation_id' => 'int',
		'city_id' => 'int',
		'owner_club_id' => 'int',
		'guest_club_id' => 'int',
		'winner_id' => 'int',
		'owner_point' => 'int',
		'guest_point' => 'int',
		'round' => 'int',
		'start_at' => 'datetime',
		'end_at' => 'datetime',
		'is_active' => 'bool',
		'is_finished' => 'bool',
		'is_canceled' => 'bool',
		'info' => 'json'
	];

	protected $fillable = [
		'tournament_id',
		'season_id',
		'stadium_id',
		'current_operation_id',
		'city_id',
		'owner_club_id',
		'guest_club_id',
		'winner_id',
		'owner_point',
		'guest_point',
		'round',
		'start_at',
		'end_at',
		'is_active',
		'is_finished',
		'is_canceled',
		'cancel_reason',
		'info'
	];

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class, 'current_operation_id');
	}

	public function ownerClub()
	{
		return $this->belongsTo(Club::class, 'owner_club_id');
	}

	public function guestClub()
	{
		return $this->belongsTo(Club::class, 'guest_club_id');
	}

	public function winner()
	{
		return $this->belongsTo(Club::class, 'winner_id');
	}

	public function season()
	{
		return $this->belongsTo(Season::class);
	}

	public function stadium()
	{
		return $this->belongsTo(Stadium::class);
	}

	public function tournament()
	{
		return $this->belongsTo(Tournament::class);
	}

	public function judge_requirements()
	{
		return $this->hasMany(JudgeRequirement::class, 'match_id');
	}

	public function match_judges()
	{
		return $this->hasMany(MatchJudge::class, 'match_id');
	}

	public function match_logists()
	{
		return $this->hasMany(MatchLogist::class, 'match_id');
	}

	public function operations()
	{
		return $this->belongsToMany(Operation::class, 'match_operation_logs', 'match_id', 'to_operation_id')
					->withPivot('id', 'from_operation_id', 'performed_by_id', 'comment')
					->withTimestamps();
	}

	public function match_protocol_requirements()
	{
		return $this->hasMany(MatchProtocolRequirement::class, 'match_id');
	}

	public function match_report_documents()
	{
		return $this->hasMany(MatchReportDocument::class, 'match_id');
	}

	public function trips()
	{
		return $this->hasMany(Trip::class, 'match_id');
	}
}

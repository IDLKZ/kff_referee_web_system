<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchOperationLog
 *
 * @property int $id
 * @property int $match_id
 * @property int|null $from_operation_id
 * @property int $to_operation_id
 * @property int $performed_by_id
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Operation $operation
 * @property Match $match
 * @property User $user
 *
 * @package App\Models
 */
class MatchOperationLog extends Model
{
	protected $table = 'match_operation_logs';

	protected $casts = [
		'match_id' => 'int',
		'from_operation_id' => 'int',
		'to_operation_id' => 'int',
		'performed_by_id' => 'int',
	];

	protected $fillable = [
		'match_id',
		'from_operation_id',
		'to_operation_id',
		'performed_by_id',
		'comment',
	];

	public function operation()
	{
		return $this->belongsTo(Operation::class, 'to_operation_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchModel::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'performed_by_id');
	}
}

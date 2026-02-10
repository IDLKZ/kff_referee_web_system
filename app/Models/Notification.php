<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array|null $data
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'notifications';

	protected $casts = [
		'user_id' => 'int',
		'notifiable_id' => 'int',
		'data' => 'json',
		'read_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'type',
		'notifiable_type',
		'notifiable_id',
		'data',
		'read_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

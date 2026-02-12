<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RoleOperation
 * 
 * @property int $role_id
 * @property int $operation_id
 * 
 * @property Operation $operation
 * @property Role $role
 *
 * @package App\Models
 */
class RoleOperation extends Model
{
	protected $table = 'role_operations';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'role_id' => 'int',
		'operation_id' => 'int'
	];

	protected $fillable = [
		'role_id',
		'operation_id',
	];

	public function operation()
	{
		return $this->belongsTo(Operation::class);
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}
}

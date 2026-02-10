<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Operation
 *
 * @property int $id
 * @property int $category_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property bool $is_first
 * @property bool $is_last
 * @property bool $can_reject
 * @property bool $is_active
 * @property int $result
 * @property int|null $previous_id
 * @property int|null $next_id
 * @property int|null $on_reject_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CategoryOperation $category_operation
 * @property Operation|null $operation
 * @property Collection|Match[] $matches
 * @property Collection|Operation[] $operations
 * @property Collection|Role[] $roles
 *
 * @package App\Models
 */
class Operation extends Model
{
	protected $table = 'operations';

	protected $casts = [
		'category_id' => 'int',
		'is_first' => 'bool',
		'is_last' => 'bool',
		'can_reject' => 'bool',
		'is_active' => 'bool',
		'result' => 'int',
		'previous_id' => 'int',
		'next_id' => 'int',
		'on_reject_id' => 'int'
	];

	protected $fillable = [
		'category_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'is_first',
		'is_last',
		'can_reject',
		'is_active',
		'result',
		'previous_id',
		'next_id',
		'on_reject_id'
	];

	public function category_operation()
	{
		return $this->belongsTo(CategoryOperation::class, 'category_id');
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class, 'previous_id');
	}

	public function matches()
	{
		return $this->hasMany(MatchModel::class, 'current_operation_id');
	}

	public function operations()
	{
		return $this->hasMany(Operation::class, 'previous_id');
	}

	public function roles()
	{
		return $this->belongsToMany(Role::class, 'role_operations');
	}
}

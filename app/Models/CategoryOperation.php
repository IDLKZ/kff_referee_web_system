<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryOperation
 * 
 * @property int $id
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string $value
 * @property bool $is_first
 * @property bool $is_last
 * @property int|null $previous_id
 * @property int|null $next_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CategoryOperation|null $category_operation
 * @property Collection|CategoryOperation[] $category_operations
 * @property Collection|Operation[] $operations
 *
 * @package App\Models
 */
class CategoryOperation extends Model
{
	protected $table = 'category_operations';

	protected $casts = [
		'is_first' => 'bool',
		'is_last' => 'bool',
		'previous_id' => 'int',
		'next_id' => 'int'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'is_first',
		'is_last',
		'previous_id',
		'next_id'
	];

	public function category_operation()
	{
		return $this->belongsTo(CategoryOperation::class, 'previous_id');
	}

	public function category_operations()
	{
		return $this->hasMany(CategoryOperation::class, 'previous_id');
	}

	public function operations()
	{
		return $this->hasMany(Operation::class, 'category_id');
	}
}

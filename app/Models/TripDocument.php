<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TripDocument
 * 
 * @property int $id
 * @property int $trip_id
 * @property int $file_id
 * @property string $title
 * @property string|null $info
 * @property bool $is_active
 * @property float|null $price
 * @property float|null $qty
 * @property float|null $total_price
 * @property int|null $logist_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property File $file
 * @property User|null $user
 * @property Trip $trip
 *
 * @package App\Models
 */
class TripDocument extends Model
{
	protected $table = 'trip_documents';

	protected $casts = [
		'trip_id' => 'int',
		'file_id' => 'int',
		'is_active' => 'bool',
		'price' => 'float',
		'qty' => 'float',
		'total_price' => 'float',
		'logist_id' => 'int'
	];

	protected $fillable = [
		'trip_id',
		'file_id',
		'title',
		'info',
		'is_active',
		'price',
		'qty',
		'total_price',
		'logist_id'
	];

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'logist_id');
	}

	public function trip()
	{
		return $this->belongsTo(Trip::class);
	}
}

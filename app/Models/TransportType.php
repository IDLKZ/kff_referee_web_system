<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransportType
 * 
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $value
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TripMigration[] $trip_migrations
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class TransportType extends Model
{
	protected $table = 'transport_types';

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

	public function trip_migrations()
	{
		return $this->hasMany(TripMigration::class);
	}

	public function trips()
	{
		return $this->hasMany(Trip::class);
	}
}

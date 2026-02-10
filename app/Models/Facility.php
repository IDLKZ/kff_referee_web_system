<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Facility
 * 
 * @property int $id
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|RoomFacility[] $room_facilities
 *
 * @package App\Models
 */
class Facility extends Model
{
	protected $table = 'facilities';

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en'
	];

	public function room_facilities()
	{
		return $this->hasMany(RoomFacility::class);
	}
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClubStadium
 * 
 * @property int $club_id
 * @property int $stadium_id
 * 
 * @property Club $club
 * @property Stadium $stadium
 *
 * @package App\Models
 */
class ClubStadium extends Model
{
	protected $table = 'club_stadiums';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'club_id' => 'int',
		'stadium_id' => 'int'
	];

	protected $fillable = [
		'club_id',
		'stadium_id',
	];

	public function club()
	{
		return $this->belongsTo(Club::class);
	}

	public function stadium()
	{
		return $this->belongsTo(Stadium::class);
	}
}

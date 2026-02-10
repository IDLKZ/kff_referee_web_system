<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * 
 * @property int $id
 * @property string $filename
 * @property string $file_path
 * @property int|null $file_size_bytes
 * @property string|null $content_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ClubType[] $club_types
 * @property Collection|Club[] $clubs
 * @property Collection|Country[] $countries
 * @property Collection|HotelRoom[] $hotel_rooms
 * @property Collection|Hotel[] $hotels
 * @property Collection|MatchReportDocument[] $match_report_documents
 * @property Collection|Stadium[] $stadiums
 * @property Collection|Tournament[] $tournaments
 * @property Collection|TripDocument[] $trip_documents
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class File extends Model
{
	protected $table = 'files';

	protected $casts = [
		'file_size_bytes' => 'int'
	];

	protected $fillable = [
		'filename',
		'file_path',
		'file_size_bytes',
		'content_type'
	];

	public function club_types()
	{
		return $this->hasMany(ClubType::class);
	}

	public function clubs()
	{
		return $this->hasMany(Club::class);
	}

	public function countries()
	{
		return $this->hasMany(Country::class, 'image_id');
	}

	public function hotel_rooms()
	{
		return $this->hasMany(HotelRoom::class);
	}

	public function hotels()
	{
		return $this->hasMany(Hotel::class);
	}

	public function match_report_documents()
	{
		return $this->hasMany(MatchReportDocument::class);
	}

	public function stadiums()
	{
		return $this->hasMany(Stadium::class);
	}

	public function tournaments()
	{
		return $this->hasMany(Tournament::class);
	}

	public function trip_documents()
	{
		return $this->hasMany(TripDocument::class);
	}

	public function users()
	{
		return $this->hasMany(User::class, 'image_id');
	}
}

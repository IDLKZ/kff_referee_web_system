<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property int $id
 * @property int|null $role_id
 * @property int|null $image_id
 * @property string $last_name
 * @property string $first_name
 * @property string|null $patronymic
 * @property string $phone
 * @property string $email
 * @property string $username
 * @property int $sex
 * @property string|null $iin
 * @property Carbon|null $birth_date
 * @property string|null $password_hash
 * @property string|null $remember_token
 * @property bool $is_active
 * @property bool $is_verified
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property File|null $file
 * @property Role|null $role
 * @property Collection|JudgeCity[] $judge_cities
 * @property Collection|MatchJudge[] $match_judges
 * @property Collection|MatchLogist[] $match_logists
 * @property Collection|MatchOperationLog[] $match_operation_logs
 * @property Collection|MatchReportDocument[] $match_report_documents
 * @property Collection|Notification[] $notifications
 * @property Collection|TripDocument[] $trip_documents
 * @property Collection|TripHotel[] $trip_hotels
 * @property Collection|TripMigration[] $trip_migrations
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use SoftDeletes;
	protected $table = 'users';

	protected $casts = [
		'role_id' => 'int',
		'image_id' => 'int',
		'sex' => 'int',
		'birth_date' => 'datetime',
		'is_active' => 'bool',
		'is_verified' => 'bool'
	];

	protected $hidden = [
		'password_hash',
		'remember_token',
	];

	protected $fillable = [
		'role_id',
		'image_id',
		'last_name',
		'first_name',
		'patronymic',
		'phone',
		'email',
		'username',
		'sex',
		'iin',
		'birth_date',
		'password_hash',
		'remember_token',
		'is_active',
		'is_verified'
	];

	public function getAuthPassword(): string
	{
		return $this->password_hash;
	}

	public function hasPermission(string $permissionValue): bool
	{
		return $this->role
			&& $this->role->permissions()->where('value', $permissionValue)->exists();
	}

	public function file()
	{
		return $this->belongsTo(File::class, 'image_id');
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function judge_cities()
	{
		return $this->hasMany(JudgeCity::class);
	}

	public function match_judges()
	{
		return $this->hasMany(MatchJudge::class, 'judge_id');
	}

	public function match_logists()
	{
		return $this->hasMany(MatchLogist::class, 'logist_id');
	}

	public function match_operation_logs()
	{
		return $this->hasMany(MatchOperationLog::class, 'performed_by_id');
	}

	public function match_report_documents()
	{
		return $this->hasMany(MatchReportDocument::class, 'judge_id');
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class);
	}

	public function trip_documents()
	{
		return $this->hasMany(TripDocument::class, 'logist_id');
	}

	public function trip_hotels()
	{
		return $this->hasMany(TripHotel::class, 'logist_id');
	}

	public function trip_migrations()
	{
		return $this->hasMany(TripMigration::class, 'logist_id');
	}

	public function trips()
	{
		return $this->hasMany(Trip::class, 'logist_id');
	}
}

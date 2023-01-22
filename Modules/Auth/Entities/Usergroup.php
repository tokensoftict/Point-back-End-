<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Auth\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Usergroup
 *
 * @property int $id
 * @property string $name
 * @property bool $status
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Permission[] $permissions
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Usergroup extends Model
{
	use SoftDeletes;
	protected $table = 'usergroups';

	protected $casts = [
		'status' => 'bool'
	];

	protected $fillable = [
		'name',
		'status'
	];

	public function permissions()
	{
		return $this->hasMany(Permission::class);
	}

	public function users()
	{
		return $this->hasMany(User::class);
	}

    public function group_tasks()
    {
        return $this->belongsToMany(Task::class, 'permissions')->withTimestamps();
    }
}

<?php


namespace Modules\Settings\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;

/**
 * Class Userbranchesmapper
 *
 * @property int $id
 * @property int $user_id
 * @property int $branch_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Branch $branch
 * @property User $user
 *
 * @package App\Models
 */
class Userbranchesmapper extends Model
{
	protected $table = 'userbranchesmapper';

	protected $casts = [
		'user_id' => 'int',
		'branch_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'branch_id'
	];

	public function branch()
	{
		return $this->belongsTo(Branch::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

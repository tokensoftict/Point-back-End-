<?php

namespace Modules\Settings\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Bank
 *
 * @property int $id
 * @property string $name
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Bank extends Model
{


	protected $table = 'banks';

	protected $fillable = [
		'name',
		'status'
	];
}

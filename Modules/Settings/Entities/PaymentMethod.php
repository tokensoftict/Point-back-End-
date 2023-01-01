<?php

namespace Modules\Settings\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentMethod
 *
 * @property int $id
 * @property string|null $name
 * @property bool|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PaymentMethod extends Model
{


	protected $table = 'payment_method';

	protected $casts = [
		'status' => 'bool'
	];

	protected $fillable = [
		'name',
		'status'
	];

    public static $fields  = [
        'name',
        'status'
    ];

    public static $validation  = [
        'name' => 'required'
    ];



}

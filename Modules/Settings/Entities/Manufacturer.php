<?php



namespace Modules\Settings\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Manufacturer
 *
 * @property int $id
 * @property string|null $name
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Manufacturer extends Model
{
	protected $table = 'manufacturers';

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

<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Settings\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\StockModule\Entities\Stock;

/**
 * Class Category
 *
 * @property int $id
 * @property string|null $name
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Stock[] $stocks
 *
 * @package App\Models
 */
class Category extends Model
{
	protected $table = 'categories';

	protected $casts = [
		'status' => 'bool'
	];

    public static $fields  = [
        'name',
        'status'
    ];

    public static $validation  = [
        'name' => 'required'
    ];

	protected $fillable = [
		'name',
		'status'
	];

	public function stocks()
	{
		return $this->hasMany(Stock::class);
	}
}

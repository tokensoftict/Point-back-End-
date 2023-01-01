<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bakeryproduction
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $production_date
 * @property Carbon|null $production_time
 * @property int $status_id
 * @property string|null $remark
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Status $status
 * @property User $user
 * @property Collection|BakeryProductionMaterialItem[] $bakery_production_material_items
 * @property Collection|BakeryProductionProductsItem[] $bakery_production_products_items
 *
 * @package App\Models
 */
class Bakeryproduction extends Model
{
	protected $table = 'bakeryproductions';

	protected $casts = [
		'status_id' => 'int',
		'user_id' => 'int'
	];

	protected $dates = [
		'production_date',
		'production_time'
	];

	protected $fillable = [
		'name',
		'production_date',
		'production_time',
		'status_id',
		'remark',
		'user_id'
	];

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function bakery_production_material_items()
	{
		return $this->hasMany(BakeryProductionMaterialItem::class);
	}

	public function bakery_production_products_items()
	{
		return $this->hasMany(BakeryProductionProductsItem::class);
	}
}

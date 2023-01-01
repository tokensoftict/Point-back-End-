<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\BakeryManager\Entities;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\StockModule\Entities\Stock;

/**
 * Class BakeryProductionProductsItem
 *
 * @property int $id
 * @property int $stock_id
 * @property int $bakeryproduction_id
 * @property int $status_id
 * @property Carbon $production_date
 * @property Carbon|null $production_time
 * @property float $quantity
 * @property float $selling_price
 * @property float $total
 * @property float $recycle
 * @property float $roughs
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Bakeryproduction $bakeryproduction
 * @property Status $status
 * @property Stock $stock
 *
 * @package App\Models
 */
class BakeryProductionProductsItem extends Model
{
    protected $table = 'bakery_production_products_items';

    protected $casts = [
        'stock_id' => 'int',
        'bakeryproduction_id' => 'int',
        'status_id' => 'int',
        'quantity' => 'float',
        'selling_price' => 'float',
        'total' => 'float',
        'recycle' => 'float',
        'roughs' => 'float'
    ];

    protected $dates = [
        'production_date',
        'production_time'
    ];

    protected $fillable = [
        'stock_id',
        'bakeryproduction_id',
        'status_id',
        'production_date',
        'production_time',
        'quantity',
        'selling_price',
        'total',
        'recycle',
        'roughs'
    ];


	public function bakeryproduction()
	{
		return $this->belongsTo(Bakeryproduction::class);
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function stock()
	{
		return $this->belongsTo(Stock::class);
	}
}

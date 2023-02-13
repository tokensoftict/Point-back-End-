<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\BakeryManager\Entities;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Branch;
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
 * @property float $extimate_quantity
 * @property float $extimate_total
 * @property float $quantity
 * @property float $total
 * @property float $selling_price
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
        'estimate_quantity' => 'float',
        'selling_price' => 'float',
        'estimate_total' => 'float',
        'total' => 'float',
        'quantity' => 'float'
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
        'estimate_quantity',
        'selling_price',
        'estimate_total',
        'total',
        'quantity',
        'branch_id'
    ];


	public function bakeryproduction()
	{
		return $this->belongsTo(Bakeryproduction::class);
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

	public function stock()
	{
		return $this->belongsTo(Stock::class);
	}
}

<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\BakeryManager\Entities;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Branch;

/**
 * Class BakeryProductionMaterialItem
 *
 * @property int $id
 * @property int $bakeryproduction_id
 * @property int $rawmaterial_id
 * @property int $status_id
 * @property Carbon $production_date
 * @property Carbon|null $production_time
 * @property float $quantity
 * @property float $cost_price
 * @property float $total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Bakeryproduction $bakeryproduction
 * @property Rawmaterial $rawmaterial
 * @property Status $status
 *
 * @package App\Models
 */
class BakeryProductionMaterialItem extends Model
{
    protected $table = 'bakery_production_material_items';

    protected $casts = [
        'bakeryproduction_id' => 'int',
        'rawmaterial_id' => 'int',
        'status_id' => 'int',
        'quantity' => 'float',
        'cost_price' => 'float',
        'total' => 'float'
    ];

    protected $dates = [
        'production_date',
        'production_time'
    ];

    protected $fillable = [
        'bakeryproduction_id',
        'rawmaterial_id',
        'status_id',
        'production_date',
        'production_time',
        'quantity',
        'cost_price',
        'total',
        'branch_id'
    ];

	public function bakeryproduction()
	{
		return $this->belongsTo(Bakeryproduction::class);
	}

	public function rawmaterial()
	{
		return $this->belongsTo(Rawmaterial::class);
	}

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

	public function status()
	{
		return $this->belongsTo(Status::class);
	}
}

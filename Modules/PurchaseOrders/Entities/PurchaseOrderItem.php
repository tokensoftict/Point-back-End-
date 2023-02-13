<?php



namespace Modules\PurchaseOrders\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Auth\Entities\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Settings\Entities\Branch;

/**
 * Class PurchaseOrderItem
 *
 * @property int $id
 * @property int $purchase_order_id
 * @property string $purchase_type
 * @property int $purchase_id
 * @property string $batch_type
 * @property int $batch_id
 * @property Carbon|null $expiry_date
 * @property string|null $store
 * @property int $qty
 * @property float|null $cost_price
 * @property float|null $selling_price
 * @property int|null $added_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 * @property PurchaseOrder $purchase_order
 *
 * @package App\Models
 */
class PurchaseOrderItem extends Model
{
	protected $table = 'purchase_order_items';

	protected $casts = [
		'purchase_order_id' => 'int',
		'purchase_id' => 'int',
		'batch_id' => 'int',
		'qty' => 'int',
		'cost_price' => 'float',
		'selling_price' => 'float',
		'added_by' => 'int'
	];

    protected $with = ["purchase","batch"];

	protected $dates = [
		'expiry_date'
	];

	protected $fillable = [
		'purchase_order_id',
		'purchase_type',
		'purchase_id',
		'batch_type',
		'batch_id',
		'expiry_date',
		'store',
		'qty',
		'cost_price',
		'selling_price',
		'added_by',
        'branch_id'
	];

	public function user() : BelongsTo
    {
		return $this->belongsTo(User::class, 'added_by');
	}

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

	public function purchase_order() : BelongsTo
    {
		return $this->belongsTo(PurchaseOrder::class);
	}

    public function purchase() : MorphTo
    {
        return $this->morphTo();
    }


    public function batch() : MorphTo
    {
        return $this->morphTo();
    }

}

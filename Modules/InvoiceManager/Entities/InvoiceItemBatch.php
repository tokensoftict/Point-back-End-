<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\InvoiceManager\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\CustomerManager\Entities\Customer;
use Modules\Settings\Entities\Branch;
use Modules\StockModule\Entities\Stock;


/**
 * Class InvoiceItemBatch
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $invoice_item_id
 * @property int|null $stock_id
 * @property int|null $stockbatch_id
 * @property int|null $customer_id
 * @property float $cost_price
 * @property float $selling_price
 * @property float $profit
 * @property int $quantity
 * @property Carbon $invoice_date
 * @property Carbon $sales_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Customer|null $customer
 * @property Invoice $invoice
 * @property InvoiceItem $invoice_item
 * @property Stock|null $stock
 * @property Stockbatch|null $stockbatch
 *
 * @package App\Models
 */
class InvoiceItemBatch extends Model
{
    protected $table = 'invoice_item_batches';

    protected $casts = [
        'invoice_id' => 'int',
        'invoice_item_id' => 'int',
        'stock_id' => 'int',
        'stockbatch_id' => 'int',
        'customer_id' => 'int',
        'cost_price' => 'float',
        'selling_price' => 'float',
        'profit' => 'float',
        'quantity' => 'int'
    ];

    protected $dates = [
        'invoice_date',
        'sales_time'
    ];

    protected $fillable = [
        'invoice_id',
        'invoice_item_id',
        'stock_id',
        'stockbatch_id',
        'customer_id',
        'cost_price',
        'selling_price',
        'profit',
        'quantity',
        'invoice_date',
        'sales_time',
        'branch_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoice_item()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }


}

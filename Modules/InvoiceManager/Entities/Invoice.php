<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\InvoiceManager\Entities;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Entities\User;
use Modules\CustomerManager\Entities\Customer;
use Modules\StockModule\Entities\Stock;

/**
 * Class Invoice
 *
 * @property int $id
 * @property string $invoice_number
 * @property int|null $customer_id
 * @property string|null $discount_type
 * @property float|null $discount_amount
 * @property int $status_id
 * @property float $sub_total
 * @property float $total_amount_paid
 * @property float $total_profit
 * @property float $total_cost
 * @property float $vat
 * @property float $vat_amount
 * @property int|null $created_by
 * @property int|null $last_updated_by
 * @property int|null $voided_by
 * @property Carbon $invoice_date
 * @property Carbon $sales_time
 * @property string|null $void_reason
 * @property Carbon|null $date_voided
 * @property Carbon|null $void_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 * @property Customer|null $customer
 * @property Collection|InvoiceItemBatch[] $invoice_item_batches
 * @property Collection|InvoiceItem[] $invoice_items
 *
 * @package App\Models
 */
class Invoice extends Model
{
    protected $table = 'invoices';

    protected $casts = [
        'customer_id' => 'int',
        'discount_amount' => 'float',
        'status_id' => 'int',
        'sub_total' => 'float',
        'total_amount_paid' => 'float',
        'total_profit' => 'float',
        'total_cost' => 'float',
        'vat' => 'float',
        'vat_amount' => 'float',
        'created_by' => 'int',
        'last_updated_by' => 'int',
        'voided_by' => 'int'
    ];

    protected $dates = [
        'invoice_date',
        'sales_time',
        'date_voided',
        'void_time'
    ];

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'discount_type',
        'discount_amount',
        'status_id',
        'sub_total',
        'total_amount_paid',
        'total_profit',
        'total_cost',
        'vat',
        'vat_amount',
        'created_by',
        'last_updated_by',
        'voided_by',
        'invoice_date',
        'sales_time',
        'void_reason',
        'date_voided',
        'void_time'
    ];

    public function create_user()  : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function last_updated() : BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function voided()  : BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function invoice_item_batches()
    {
        return $this->hasMany(InvoiceItemBatch::class);
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopecomplete($query)
    {
        return $query->where("status_id",Status::where("name","Complete")->first()->id);
    }

    public function scopedraft($query)
    {
        return $query->where("status_id",Status::where("name","Draft")->first()->id);
    }

    public function scopetoday($query)
    {
        return $query->where("invoice_date",dailyDate());
    }

    public function scopefilterdata($query)
    {
        if(request()->get("filter"))
        {
            foreach (json_decode(request()->get("filter"),true) as $key => $value) {
                if($key === "between")
                {
                    $query->whereBetween("invoice_date",$value );
                }
                else {
                    $query->where($key, $value);
                }
            }
        }

        return $query;
    }

    public static function createInvoice($request)
    {

        $reports = self::validateInvoiceProduct($request);

        if($reports['status'] === true) return ['errors'=>$reports["errors"],"status"=>$reports["status"]];

        $totals = self::calculateInvoiceTotal($reports);

        $invoice_data = [
            'invoice_number'=>time(),
            'customer_id'=> $request->get('customer_id'),
            'discount_type'=> "none",
            'discount_amount' => 0,
            'status_id'=>Status::where("name","Draft")->first()->id,
            'sub_total' => $totals['total_invoice_total_selling'],
            'total_amount_paid' => 0,
            'total_profit' =>  $totals['total_invoice_total_profit'],
            'total_cost' => $totals['total_invoice_total_cost'],
            'vat' => 0,
            'vat_amount' => 0,
            'created_by' => auth()->id(),
            'last_updated_by' =>auth()->id(),
            'invoice_date' =>  $request->get('date'),
            'sales_time' =>Carbon::now()->toTimeString(),
        ];


        $invoice = Invoice::create($invoice_data);

        $invoice_items_data = self::prepareInvoiceItemData(
            $reports,
            $request->get('customer_id'),
            $invoice->status_id,
            $request->get('date'),
            Carbon::now()->toTimeString(),
            $invoice
        );

        $invoice_items_batches_data = self::prepareInvoiceItemBatchesData(
            $reports,
            $request->get('customer_id'),
            $invoice->status_id,
            $request->get('date'),
            Carbon::now()->toTimeString(),
            $invoice
        );


        foreach ($invoice_items_data as $key=>$invoice_items_datum){
            $invoice
                ->invoice_items()
                ->save($invoice_items_datum)
                ->invoice_item_batches()
                ->saveMany($invoice_items_batches_data[$key]);
        }
        return $invoice;

    }

    public static function validateInvoiceProduct($request) : array
    {
        $status = false;
        $report = [];
        $errors = [];
        $prods = [];

        $products =  json_decode($request->get("items"),true);

        $product_ids =  array_column($products,'id');

        foreach ($products as $product){
            $prods[$product['id']] = $product;
        }


        $stocks = Stock::whereIn('id',$product_ids)->get();

        foreach ($stocks as $key=>$stock)
        {

            if($stock->quantity <= $prods[$stock->id]['selling_quantity'])
            {
                $status = true;
                $errors[$key] = "Not enough quantity to process ".$stock->name;
            }

            $report[$stock->id]['stock'] = $stock;
            $report[$stock->id]['prods'] = $prods[$stock->id];
        }

        return ['status'=> $status, 'data'=>$report,'errors'=> $errors];

    }


    public static function calculateInvoiceTotal($validationReports)
    {
        $stocks = $validationReports['data'];

        $invoiceTotal = [];

        $total_invoice_total_selling = 0;
        $total_invoice_total_cost = 0;
        $total_invoice_total_profit = 0;

        foreach ($stocks as $key=>$stock){

            $invoiceTotal[$key]['total_selling_price']  =  $stock['stock']->selling_price * $stock['prods']['selling_quantity'];
            $invoiceTotal[$key]['total_cost_price']  =  $stock['stock']->cost_price * $stock['prods']['selling_quantity'];
            $invoiceTotal[$key]['total_profit'] =  $invoiceTotal[$key]['total_selling_price'] -  $invoiceTotal[$key]['total_cost_price'];
            $total_invoice_total_selling += $invoiceTotal[$key]['total_selling_price'];
            $total_invoice_total_cost += $invoiceTotal[$key]['total_cost_price'] ;
            $total_invoice_total_profit+=$invoiceTotal[$key]['total_profit'];

        }

        return [
            'total_invoice_total_selling'=>$total_invoice_total_selling,
            'total_invoice_total_cost' =>$total_invoice_total_cost,
            'total_invoice_total_profit' =>$total_invoice_total_profit
        ];

    }


    public static function prepareInvoiceItemData($validationReports,$customer_id, $status, $invoice_date,$sales_time, $invoice)
    {

        $stocks = $validationReports['data'];

        $invoiceItems = [];

        foreach ($stocks as $key=>$stock){

            $total_selling_price  =   $stock['stock']->selling_price * $stock['prods']['selling_quantity'];
            $total_cost_price  =  $stock['stock']->cost_price * $stock['prods']['selling_quantity'];
            $total_profit =   $total_selling_price -  $total_cost_price;

            //remove stock quantity from database
            $stock['stock']->quantity -= $stock['prods']['selling_quantity'];
            $stock['stock']->save();
            //ending of removeing quantity
            $invoiceItems[$key] =  new InvoiceItem([
                'invoice_id'=> $invoice->id,
                'stock_id'=>$key,
                'quantity'=>$stock['prods']['selling_quantity'],
                'customer_id'=>$customer_id,
                'status' => $invoice->status_id,
                'added_by'=> auth()->id(),
                'invoice_date' =>$invoice_date,
                'sales_time' =>$sales_time,
                'cost_price'=>$stock['stock']->cost_price,
                'selling_price' => $stock['stock']->selling_price,
                'profit'=>( $stock['stock']->selling_price - $stock['stock']->cost_price),
                'total_selling_price' =>$total_selling_price,
                'total_cost_price' => $total_cost_price,
                'total_profit'=>$total_profit,
                'discount_type'=>'none',
                'discount_amount'=>0,
            ]);


        }

        return $invoiceItems;
    }

    public static function prepareInvoiceItemBatchesData($validationReports,$customer_id, $status, $invoice_date,$sales_time, $invoice){

        $invoiceItemBatches = [];

        $stocks = $validationReports['data'];

        foreach ($stocks as $key=>$stock){
            $invoiceItemBatches[$key][] =  new InvoiceItemBatch( [
                'invoice_id'=> $invoice->id,
                'stock_id' => $key,
                'stockbatch_id'=>NULL,
                'cost_price'=> ($stock['stock']->cost_price),
                'selling_price'=> ( $stock['stock']->selling_price),
                'profit'=>($stock['stock']->selling_price - $stock['stock']->cost_price),
                'quantity' =>$stock['prods']['selling_quantity'],
                'invoice_date' => $invoice_date,
                'sales_time' =>$sales_time,
                'customer_id' =>$customer_id,
            ]);
        }

        return $invoiceItemBatches;

    }



    public static function updateInvoice($request, $invoice)
    {
        $invoice->invoice_item_batches()->delete();
        $invoice->invoice_items()->delete();

        $reports = self::validateInvoiceProduct($request);

        if($reports['status'] === true) return ['errors'=>$reports["errors"],"status"=>$reports["status"]];

        $totals = self::calculateInvoiceTotal($reports);

        $invoice_data = [
            'customer_id'=> $request->get('customer_id'),
            'discount_type'=> "none",
            'discount_amount' => 0,
            'status_id'=>Status::where("name","Draft")->first()->id,
            'sub_total' => $totals['total_invoice_total_selling'],
            'total_amount_paid' => 0,
            'total_profit' =>  $totals['total_invoice_total_profit'],
            'total_cost' => $totals['total_invoice_total_cost'],
            'vat' => 0,
            'vat_amount' => 0,
            'last_updated_by' =>auth()->id(),
        ];

        $invoice->update($invoice_data);

        $invoice_items_data = self::prepareInvoiceItemData(
            $reports,
            $request->get('customer_id'),
            $invoice->status_id,
            $request->get('date'),
            Carbon::now()->toTimeString(),
            $invoice
        );

        $invoice_items_batches_data = self::prepareInvoiceItemBatchesData(
            $reports,
            $request->get('customer_id'),
            $invoice->status_id,
            $request->get('date'),
            Carbon::now()->toTimeString(),
            $invoice
        );


        foreach ($invoice_items_data as $key=>$invoice_items_datum){
            $invoice
                ->invoice_items()
                ->save($invoice_items_datum)
                ->invoice_item_batches()
                ->saveMany($invoice_items_batches_data[$key]);
        }

        return $invoice;

    }
}

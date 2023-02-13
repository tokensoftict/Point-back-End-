<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\PaymentManager\Entities;


use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\CustomerManager\Entities\Customer;
use Modules\InvoiceManager\Entities\Invoice;

/**
 * Class Payment
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $customer_id
 * @property string|null $invoice_number
 * @property string $invoice_type
 * @property int $invoice_id
 * @property float $subtotal
 * @property float $total_paid
 * @property float $discount
 * @property Carbon|null $payment_time
 * @property Carbon|null $payment_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Customer|null $customer
 * @property User|null $user
 * @property Collection|PaymentMethodTable[] $payment_method_tables
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payments';

	protected $casts = [
		'user_id' => 'int',
		'customer_id' => 'int',
		'invoice_id' => 'int',
		'subtotal' => 'float',
		'total_paid' => 'float',
        'discount' => 'float'
	];

	protected $dates = [
		'payment_time',
		'payment_date'
	];

	protected $fillable = [
		'user_id',
		'customer_id',
		'invoice_number',
		'invoice_type',
		'invoice_id',
		'subtotal',
		'total_paid',
        'discount',
		'payment_time',
		'payment_date',
        'branch_id'
	];

    public function getTotalPaidAttribute()
    {
        return $this->subtotal - $this->discount;
    }

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function payment_method_tables()
	{
		return $this->hasMany(PaymentMethodTable::class);
	}

    public function scopetoday($query)
    {
        return $query->where("payment_date",dailyDate());
    }

    public function scopefilterdata($query)
    {
        if(request()->get("filter"))
        {
            foreach (json_decode(request()->get("filter"),true) as $key => $value) {
                if($key === "between")
                {
                    $query->whereBetween("payment_date",$value );
                }
                else {
                    $query->where($key, $value);
                }
            }
        }

        return $query;
    }



    public function invoice(){

        return $this->morphTo();
    }

    public static function createPayment($request, $invoice, $invoiceType)
    {

        $payment = Payment::create([
            'user_id' => auth()->id(),
            'customer_id' => $invoice->customer_id,
            'invoice_number' =>  $invoice->invoice_number,
            'invoice_id' =>  $invoice->id,
            'invoice_type'=> $invoiceType,
            'subtotal' =>  $invoice->sub_total,
            'total_paid' =>  $invoice->sub_total - $request->get("discount"),
            'discount' => $request->get("discount"),
            'payment_time' =>  Carbon::now()->toTimeString(),
            'payment_date' =>  dailyDate(),
            'branch_id' => getBranch()->id
        ]);

        $payment_data = json_decode($request->get("payment_data"),true);

        if($request->get("payment_method") === "split")
        {
            $split_payments = [];
            foreach($payment_data as $key=>$method)
            {
                if($method['amount'] > 0)
                {
                    if ($key != 4) {
                        $split_payments[] = new PaymentMethodTable([
                            'user_id' => auth()->id(),
                            'customer_id' => $invoice->customer_id,
                            "payment_method_id" => $key,
                            'invoice_id' => $invoice->id,
                            'invoice_type' => $invoiceType,
                            'payment_date' => dailyDate(),
                            'amount' => $method['amount'],
                            'payment_info' => json_encode($method),
                            'branch_id' => getBranch()->id
                        ]);
                    }
                    else {
                        $credit_payment_info = [
                            'user_id' => auth()->id(),
                            'customer_id' => $invoice->customer_id,
                            'payment_method_id' => $key,
                            'invoice_id' => $invoice->id,
                            'invoice_type' =>$invoiceType,
                            'warehousestore_id' => NULL,
                            'payment_date' => dailyDate(),
                            'amount' => $method['amount'],
                            'payment_info' => json_encode($method),
                            'branch_id' => getBranch()->id
                        ];
                    }

                }
            }

            $payment->payment_method_tables()->saveMany( $split_payments);

            if(isset($credit_payment_info)) {
                $payment_method_id = $payment->payment_method_tables()->save(new PaymentMethodTable($credit_payment_info));

                $credit_log = [
                    'payment_id' => $payment->id,
                    'user_id' => auth()->id(),
                    'payment_method_id' => 1, // just to get rid of error for payment method table id insted of payment method
                    'customer_id' =>$invoice->customer_id,
                    'invoice_number' =>$invoice->invoice_number,
                    'invoice_id' => $invoice->id,
                    'amount' => -($payment_method_id->amount),
                    'payment_date' => dailyDate(),
                    'branch_id' => getBranch()->id
                ];
                CreditPaymentLog::create($credit_log);
            }
        }
        else
        {
            $payment->payment_method_tables()->save(new PaymentMethodTable([
                'user_id' => auth()->id(),
                'customer_id' => $invoice->customer_id,
                "payment_method_id" => $request->get("payment_method"),
                'invoice_id' =>  $invoice->id,
                'invoice_type'=> $invoiceType,
                'payment_date' =>  dailyDate(),
                'amount' => $invoice->sub_total - $request->get("discount"),
                'payment_info' => json_encode($payment_data),
                'branch_id' => getBranch()->id
            ]));

            if($request->get("payment_method") === 4)
            {
                $credit_log = [
                    'payment_id' => $payment->id,
                    'user_id' => auth()->id(),
                    'payment_method_id' => $request->get("payment_method"),
                    'customer_id' => $invoice->customer_id,
                    'invoice_number' => $invoice->invoice_number,
                    'invoice_id' =>  $invoice->id,
                    'amount' => -($invoice->sub_total - $request->get("discount")),
                    'payment_date' => dailyDate(),
                    'branch_id' => getBranch()->id
                ];

                CreditPaymentLog::create($credit_log);
            }

        }

        if($invoiceType === Invoice::class)
        {
            $invoice->total_amount_paid = ($invoice->sub_total - $request->get("discount"));
            $invoice->status_id = Status::where("name", "paid")->first()->id;
            $invoice->update();
        }

        return $payment;
    }



}

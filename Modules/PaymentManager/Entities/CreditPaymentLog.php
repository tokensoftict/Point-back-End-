<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\PaymentManager\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\CustomerManager\Entities\Customer;
use Modules\InvoiceManager\Entities\Invoice;
use Modules\Settings\Entities\PaymentMethod;

/**
 * Class CreditPaymentLog
 *
 * @property int $id
 * @property int $payment_id
 * @property int|null $user_id
 * @property int $payment_method_id
 * @property int|null $customer_id
 * @property string|null $invoice_number
 * @property int|null $invoice_id
 * @property float $amount
 * @property Carbon $payment_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Customer|null $customer
 * @property Invoice|null $invoice
 * @property Payment $payment
 * @property PaymentMethodTable $payment_method_table
 * @property User|null $user
 *
 * @package App\Models
 */
class CreditPaymentLog extends Model
{
	protected $table = 'credit_payment_logs';

	protected $casts = [
		'payment_id' => 'int',
		'user_id' => 'int',
		'payment_method_id' => 'int',
		'customer_id' => 'int',
		'invoice_id' => 'int',
		'amount' => 'float'
	];

	protected $dates = [
		'payment_date'
	];

	protected $fillable = [
		'payment_id',
		'user_id',
		'payment_method_id',
		'customer_id',
		'invoice_number',
		'invoice_id',
		'amount',
		'payment_date',
        'branch_id'
	];

    protected $appends = ['sub_total'];


    public function getSubTotalAttribute()
    {
        return $this->amount;
    }

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function payment()
	{
		return $this->belongsTo(Payment::class);
	}

	public function payment_method_table()
	{
		return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}

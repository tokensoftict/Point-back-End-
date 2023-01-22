<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\PaymentManager\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\User;
use Modules\CustomerManager\Entities\Customer;
use Modules\Settings\Entities\PaymentMethod;

/**
 * Class PaymentMethodTable
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $customer_id
 * @property int|null $payment_id
 * @property int|null $payment_method_id
 * @property string $invoice_type
 * @property int $invoice_id
 * @property Carbon $payment_date
 * @property float $amount
 * @property string|null $payment_info
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Customer|null $customer
 * @property Payment|null $payment
 * @property PaymentMethod|null $payment_method
 * @property User|null $user
 *
 * @package App\Models
 */
class PaymentMethodTable extends Model
{
	protected $table = 'payment_method_table';

	protected $casts = [
		'user_id' => 'int',
		'customer_id' => 'int',
		'payment_id' => 'int',
		'payment_method_id' => 'int',
		'invoice_id' => 'int',
		'amount' => 'float'
	];

	protected $dates = [
		'payment_date'
	];

	protected $fillable = [
		'user_id',
		'customer_id',
		'payment_id',
		'payment_method_id',
		'invoice_type',
		'invoice_id',
		'payment_date',
		'amount',
		'payment_info'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function payment()
	{
		return $this->belongsTo(Payment::class);
	}

	public function payment_method()
	{
		return $this->belongsTo(PaymentMethod::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}


    public function invoice(){

        return $this->morphTo();
    }


    public function scopetoday($query)
    {
        return $query->where("payment_date",dailyDate());
    }

    public function scopeignorecredit($query)
    {
        return $query->where("payment_method_id",'<>',4);
    }

    public function scopefilter($query)
    {
        foreach(request()->get("filter") as $key=>$value)
        {
            $query->where($key,$value);
        }

        return $query;
    }

    public function scopemethodWise($query, $column, $Apiresource)
    {
        $payment = PaymentMethod::all()->pluck("name","id")->toArray();
        return $Apiresource::collection($query->get())->groupBy(function($item) use ($column, $payment){
            return $payment[$item->{$column}];
        });
    }
}

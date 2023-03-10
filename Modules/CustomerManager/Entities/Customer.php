<?php

namespace Modules\CustomerManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\InvoiceManager\Entities\Invoice;
use Modules\InvoiceManager\Entities\InvoiceItem;
use Modules\InvoiceManager\Entities\InvoiceItemBatch;
use Modules\PaymentManager\Entities\CreditPaymentLog;
use Modules\PaymentManager\Entities\Payment;
use Modules\PaymentManager\Entities\PaymentMethodTable;

/**
 * Class Customer
 *
 * @property int $id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $email
 * @property string|null $address
 * @property string|null $phone_number
 * @property string|null $nok_phone_number
 * @property string|null $nok_email
 * @property string|null $nok_lastname
 * @property string|null $nok_firstname
 * @property string|null $nationality
 * @property string|null $passport_expire_date
 * @property string|null $passport_no
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|BookingReservationItem[] $booking_reservation_items
 * @property Collection|BookingReservation[] $booking_reservations
 * @property Collection|CreditPaymentLog[] $credit_payment_logs
 * @property Collection|InvoiceItemBatch[] $invoice_item_batches
 * @property Collection|InvoiceItem[] $invoice_items
 * @property Collection|Invoice[] $invoices
 * @property Collection|PaymentMethodTable[] $payment_method_tables
 * @property Collection|Payment[] $payments
 * @property Collection|ReturnLog[] $return_logs
 *
 * @package App\Models
 */

class Customer extends Model
{


    protected $table = 'customers';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'address',
        'company_name',
        'phone_number',
        'nok_phone_number',
        'nok_email',
        'nok_lastname',
        'nok_firstname',
        'nationality',
        'passport_expire_date',
        'passport_no',

        'occupation',
        'purpose_of_visit',
        'vehicle_reg_number',
        'arriving_from',
        'city',
        'state',
    ];

    public static $fields = [
        'firstname',
        'lastname',
        'email',
        'address',
        'company_name',
        'phone_number',
        'nok_phone_number',
        'nok_email',
        'nok_lastname',
        'nok_firstname',
        'nationality',
        'passport_expire_date',
        'passport_no',

        'occupation',
        'purpose_of_visit',
        'vehicle_reg_number',
        'arriving_from',
        'city',
        'state',
    ];

    protected $appends = ['credit_balance','last_payment_date'];

    public static $validate = [
        'firstname'=>'required',
        'lastname'=>'required',
        'phone_number' => 'required'
    ];

    public function getFullnameAttribute()
    {
        return $this->firstname." ".$this->lastname;
    }

    public function getLastPaymentDateAttribute()
    {
       $payment_date = $this->credit_payment_logs()->where('amount','>',0)->orderBy('id','DESC')->first();
       if(isset($payment_date->payment_date))  return $payment_date;
       return false;
    }

    public function getCreditBalanceAttribute()
    {
        return $this->credit_payment_logs()->sum('amount');
    }
/*

    public function getDepositBalanceAttribute()
    {
        return $this->customerDepositsHistory()->sum('amount') - $this->payment_method_tables()->where('payment_method_id',5)->sum('amount');
    }

    public function booking_reservation_items()
    {
        return $this->hasMany(BookingReservationItem::class);
    }

    public function booking_reservations()
    {
        return $this->hasMany(BookingReservation::class);
    }
*/
    public function credit_payment_logs()
    {
        return $this->hasMany(CreditPaymentLog::class);
    }

    public function invoice_item_batches()
    {
        return $this->hasMany(InvoiceItemBatch::class);
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payment_method_tables()
    {
        return $this->hasMany(PaymentMethodTable::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
/*
    public function return_logs()
    {
        return $this->hasMany(ReturnLog::class);
    }

    public function customerDepositsHistory()
    {
        return $this->hasMany(CustomerDepositsHistory::class);
    }
*/

    public function scopefilter($query,$string)
    {
        $string =  explode(' ', $string);

        return $query->where(function($q) use ($string){
            $q->where(function($sub) use (&$string){
                foreach ($string as $char) {
                    $sub->orwhere('firstname', 'LIKE', "%{$char}%");
                    $sub->orwhere('lastname', 'LIKE', "%{$char}%");
                    $sub->orwhere('company_name', 'LIKE', "%{$char}%");
                    $sub->orwhere('phone_number', 'LIKE', "%{$char}%");
                }
            });
        });
    }


    public function scopecreditors($query)
    {
       return  $query->get()->filter(function($customer){
           return $customer->credit_balance < 0;
       });
    }

}

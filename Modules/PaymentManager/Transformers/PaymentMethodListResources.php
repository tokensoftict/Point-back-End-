<?php

namespace Modules\PaymentManager\Transformers;

use Arr;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\InvoiceManager\Entities\Invoice;
use Modules\PaymentManager\Entities\CreditPaymentLog;

class PaymentMethodListResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public static $columns = [
        "No",
        "Customer",
        "Branch",
        "Invoice Number",
        "Payment Type",
        "Amount",
        "Date",
        "Time",
        "Created By",
    ];

    public static $payment_type = [
        Invoice::class => "Invoice Payment",
        CreditPaymentLog::class => "Credit Payment"
    ];

    public function toArray($request)
    {
        $data = [];

        Arr::set($data,"Customer",$this->customer->fullname);
        Arr::set($data,"Branch",getBranch()->name);
        Arr::set($data,"Invoice Number",$this->payment->invoice_number);
        Arr::set($data,"Amount_",$this->amount);
        Arr::set($data,"Payment Type",self::$payment_type[$this->payment->invoice_type]);
        Arr::set($data,"Amount",number_format($this->amount,2));
        Arr::set($data,"Total Paid",number_format($this->total_paid,2));
        Arr::set($data,"Date",eng_str_date($this->payment_date));
        Arr::set($data,"Time",twelve_hour_time($this->payment->payment_time));
        Arr::set($data,"Created By",$this->user->name);

        return $data;
    }
}

<?php

namespace Modules\PaymentManager\Transformers;

use Arr;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\InvoiceManager\Entities\Invoice;
use Modules\PaymentManager\Entities\CreditPaymentLog;

class PaymentListResource extends JsonResource
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
        "Invoice Number",
        "Discount",
        "Total Paid",
        "Payment Type",
        "Date",
        "Time",
        "Created By",
        "Action"
    ];


    public static $payment_type = [
            Invoice::class => "Invoice Payment",
            CreditPaymentLog::class => "Credit Payment"
        ];

    public function toArray($request)
    {
        $data =  [];

        Arr::set($data,"Customer",$this->customer->fullname);
        Arr::set($data,"total_paid",$this->total_paid);
        Arr::set($data,"Invoice Number",$this->invoice_number);
        Arr::set($data,"Total Paid",number_format($this->total_paid,2));
        Arr::set($data,"Discount",number_format($this->discount,2));

        Arr::set($data,"Payment Type",self::$payment_type[$this->invoice_type]);
        Arr::set($data,"Date",eng_str_date($this->payment_date));
        Arr::set($data,"Time",twelve_hour_time($this->payment_time));
        Arr::set($data,"Created By",$this->user->name);

        $action = [];

        if(Invoice::class == $this->invoice_type)
        {
            Arr::set($action,"Print Invoice A4", ['type'=>'external','permission'=>"/invoice//printA4",'link'=>route("invoice.print_afour",$this->invoice_id)]);

            Arr::set($action,"Print Invoice Thermal", ['type'=>'external','permission'=>"/invoice//printThermal",'link'=>route("invoice.pos_print",$this->invoice_id)]);

        }

        if(CreditPaymentLog::class == $this->invoice_type)
        {

        }

        Arr::set($data,"Action",$action);

        return $data;
    }
}

<?php

namespace Modules\PaymentManager\Transformers;

use Arr;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerBalanceSheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public static $columns = [
        "No",
        "Credit",
        "Payment",
        "Date",
       // "Action"
    ];

    public function toArray($request)
    {
        $data = [];

        Arr::set($data,'type', $this->amount < 0 ? "Credit" : "Payment");
        Arr::set($data,'amount', $this->amount);
        Arr::set($data,'date', eng_str_date($this->payment_date));
        Arr::set($data,'amount_formatted', number_format($this->amount,2));

        $action = [];

        if($this->amount > 0)
        {
            //add credit payment
        }

        Arr::set($data,'Action',$action);

        return $data;
    }
}

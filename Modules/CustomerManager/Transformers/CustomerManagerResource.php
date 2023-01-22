<?php

namespace Modules\CustomerManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data =  parent::toArray($request);

        \Arr::set($data,"name",$this->firstname." ".$this->lastname);
        \Arr::set($data,"credit_balance",number_format($this->credit_balance,2));
        \Arr::set($data,"credit_balance_formatted",number_format($this->credit_balance,2));
        \Arr::set($data,"last_payment_date", $this->last_payment_date ? str_date2($this->last_payment_date) : "");

        return $data;
    }
}

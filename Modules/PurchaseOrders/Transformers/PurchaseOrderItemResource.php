<?php

namespace Modules\PurchaseOrders\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = []; //parent::toArray($request);

        \Arr::set($data, "id", $this->purchase_id);
        \Arr::set($data, "expiry_date", mysql_str_date($this->expiry_date));
        \Arr::set($data, "cost_price_formated", number_format($this->cost_price,2));
        \Arr::set($data, "cost_price", $this->cost_price);
        \Arr::set($data, "selling_price", number_format($this->selling_price,2));
        \Arr::set($data, "quantity", $this->qty);
        \Arr::set($data, "qty", $this->qty);
        \Arr::set($data, "total", number_format(($this->cost_price * $this->qty),2));
        \Arr::set($data,"name",$this->purchase->name);

        return $data;

    }
}

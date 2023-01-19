<?php

namespace Modules\InvoiceManager\Transformers;

use Arr;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];

        Arr::set($data,"id",$this->stock_id);
        Arr::set($data,"invoice_item_id",$this->id);
        Arr::set($data,"name",$this->stock->name);
        Arr::set($data,"name",$this->stock->name);
        Arr::set($data,"cost_price",number_format($this->cost_price,2));
        Arr::set($data,"formatted_selling_price",number_format($this->selling_price,2));
        Arr::set($data,"selling_price",$this->selling_price);
        Arr::set($data,"quantity", $this->stock->quantity);
        Arr::set($data,"selling_quantity", $this->quantity);

        return $data;
    }
}

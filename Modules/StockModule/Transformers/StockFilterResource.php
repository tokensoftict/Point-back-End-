<?php

namespace Modules\StockModule\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class StockFilterResource extends JsonResource
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

        Arr::set($data,"id",$this->id);
        Arr::set($data,"name",$this->name);
        Arr::set($data,"quantity",$this->{getQuantityColumn()});
        Arr::set($data,"selling_price",$this->selling_price);

        return $data;
    }
}

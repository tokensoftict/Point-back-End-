<?php

namespace Modules\StockModule\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class StockListAvailableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public static $coloumns = [
        "name",
        "quantity",
        "Category",
        "Group",
        "status",
        "selling_price",
        "cost_price",
        "last_updated",
        "action"

    ];

    public function toArray($request)
    {
        $data =  []; //parent::toArray($request);

        Arr::set($data,"id",$this->id);
        Arr::set($data,"name",$this->name);
        Arr::set($data,"quantity",$this->quantity);
        Arr::set($data,"Category",$this->category?->name);
        Arr::set($data,"Group",$this->productgroup?->name);
        Arr::set($data,"status",$this->status);
        Arr::set($data,"selling_price",number_format($this->selling_price,2));
        Arr::set($data,"cost_price",number_format($this->cost_price,2));
        Arr::set($data,"last_updated",$this->name);
        Arr::set($data,"action",[
            "Edit" => $this->id."/edit",
            "Toggle Stock" => $this->id."/toggle",
            "Stock Report" => $this->id."/report"
        ]);
        return $data;
    }
}

<?php

namespace Modules\BakeryManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StockModule\Transformers\StockListResource;

class BakeryProductionProductItemResource extends JsonResource
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
        \Arr::set($data,"id",  $this->stock_id);
        \Arr::set($data,"product_item_id",  $this->id);
        \Arr::set($data,"name",  $this->stock->name);
        \Arr::set($data,"stock", new StockListResource($this->stock));
        \Arr::set($data,"production_date", eng_str_date($this->production_date));
        \Arr::set($data,"production_time", twelve_hour_time($this->production_date));
        \Arr::forget($data,['stock_id','bakeryproduction_id','status_id']);
        return $data;
    }
}

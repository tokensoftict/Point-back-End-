<?php

namespace Modules\BakeryManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Auth\Transformers\AuthCollection;

class BakeryProductionResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data =  [];
        \Arr::set($data,"name",$this->name);
        \Arr::set($data,"remark",$this->remark);
        \Arr::set($data,"status",["label"=>$this->status->label,"name"=>$this->status->name]);
        \Arr::set($data,"production_date",mysql_str_date($this->production_date));
        \Arr::set($data,"production_time",twelve_hour_time($this->production_time));
        \Arr::set($data,"total_material",$this->total_material);
        \Arr::set($data,"total_product",$this->total_product);
        \Arr::set($data,"profit",$this->profit);
        \Arr::set($data,"created_by",new AuthCollection($this->user));
        \Arr::set($data,"completed_by",new AuthCollection($this->completed));
        \Arr::set($data,"bakery_production_products_items",BakeryProductionProductItemResource::collection($this->bakery_production_products_items));
        \Arr::set($data,"bakery_production_material_items",BakeryProductionMaterialItemResource::collection($this->bakery_production_material_items));

        return $data;
    }
}

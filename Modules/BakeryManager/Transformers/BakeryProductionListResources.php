<?php

namespace Modules\BakeryManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Auth\Transformers\AuthCollection;

class BakeryProductionListResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public static $colunm = [
        "No",
        "name",
        "Status",
        "date",
        "time",
        "total_material",
        "total_product",
        "profit",
        "created_by",
        "completed_by",
        "Action"
    ];

    public function toArray($request)
    {
        $data =  [];
        \Arr::set($data,"id",$this->id);
        \Arr::set($data,"name",$this->name);
        \Arr::set($data,"Status",["name"=>$this->status->name,"label"=>$this->status->label]);
        \Arr::set($data,"date",eng_str_date($this->production_date));
        \Arr::set($data,"time",twelve_hour_time($this->production_time));
        \Arr::set($data,"total_material",$this->total_material);
        \Arr::set($data,"total_product",$this->status_id ==4 ? $this->total_estimate_total : $this->total_product);
        \Arr::set($data,"profit",$this->profit);
        \Arr::set($data,"created_by",new AuthCollection($this->user));
        \Arr::set($data,"completed_by",new AuthCollection($this->completed));
        \Arr::set($data,"product_count",$this->bakery_production_products_items->count());
        \Arr::set($data,"material_count",$this->bakery_production_products_items->count());
        $action = [];

        Arr::set($action,"View Production", $this->id."/show");

        if($this->status->name == "Draft")
        {
            Arr::set($action,"Edit", $this->id."/edit");
            //Arr::set($action,"Delete Production",$this->id."/remove");
        }

        if($this->status->name == "Approved")
        {
            Arr::set($action,"Complete", $this->id."/complete");
        }

        Arr::set($data,"action",$action);

        return $data;
    }
}

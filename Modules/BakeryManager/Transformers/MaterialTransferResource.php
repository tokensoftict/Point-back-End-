<?php

namespace Modules\BakeryManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Auth\Transformers\AuthCollection;

class MaterialTransferResource extends JsonResource
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
        "request_by",
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
        \Arr::set($data,"request_by",new AuthCollection($this->user));
        \Arr::set($data,"material_count",$this->bakery_production_products_items->count());
        $action = [];

        Arr::set($action,"View Transfer", $this->id."/show");

        if($this->status->name == "Draft")
        {
            //Arr::set($action,"Accept", $this->id."/edit");
            //Arr::set($action,"Decline", $this->id."/complete");
        }

        Arr::set($data,"action",$action);

        return $data;
    }
}

<?php

namespace Modules\BakeryManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BakeryProductionMaterialItemResource extends JsonResource
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
        \Arr::set($data,"id",$this->rawmaterial_id);
        \Arr::set($data,"name",$this->rawmaterial->name);
        \Arr::set($data,"rawmaterial",new MaterialFilterResource($this->rawmaterial));
        \Arr::set($data,"status",["label"=>$this->status->label,"name"=>$this->status->name]);
        \Arr::set($data,"production_date",eng_str_date($this->production_date));
        \Arr::set($data,"production_time", ($this->production_time ? twelve_hour_time($this->production_time) : ""));

        \Arr::forget($data,['bakeryproduction_id','rawmaterial_id','status_id']);

        return $data;
    }
}

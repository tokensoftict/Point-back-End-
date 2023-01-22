<?php

namespace Modules\StockModule\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class StockResource extends JsonResource
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

        $action = [];

        Arr::set($action,"Edit Stock", ['type'=>'internal','permission'=>"/stock/:id/edit",'link'=>$this->id."/edit"]);

        Arr::set($action,"Toggle Stock", ['type'=>'external','permission'=>"/stock/:id/toggle",'link'=>$this->id."/toggle"]);

        Arr::set($data,"action",$action);

        return $data;

    }
}

<?php

namespace Modules\CustomerManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerManagerResource extends JsonResource
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

        \Arr::set($data,"name",$this->firstname." ".$this->lastname);

        return $data;
    }
}

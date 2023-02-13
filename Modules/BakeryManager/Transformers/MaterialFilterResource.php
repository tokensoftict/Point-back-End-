<?php

namespace Modules\BakeryManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class MaterialFilterResource extends JsonResource
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

        Arr::forget($data,"created_at");
        Arr::forget($data,"updated_at");
        Arr::set($data, 'quantity', $this->{getQuantityColumn()});
        Arr::set($data,"cost_price",$this->cost_price);
        Arr::set($data,"materialtype",new MaterialTypeResource($this->materialtype));

        return $data;
    }
}

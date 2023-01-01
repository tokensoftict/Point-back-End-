<?php

namespace Modules\BakeryManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use App\Models\Materialtype;

class RawMaterialResource extends JsonResource
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

        Arr::set($data,"created_at",str_date2($this->created_at,false));
        Arr::set($data,"updated_at",str_date2($this->updated_at,false));

        Arr::set($data,"materialtype",new MaterialTypeResource($this->materialtype));

        return $data;
    }
}

<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserGroupListResource extends JsonResource
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

        \Arr::forget($data,["deleted_at","created_at","updated_at"]);

        return $data;
    }
}

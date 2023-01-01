<?php

namespace Modules\PurchaseOrders\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Settings\Transformers\SupplierResource;

class PurchaseOrderWithItemResources extends JsonResource
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

        \Arr::set($data,"id", $this->id);
        \Arr::set($data,"supplier",new SupplierResource($this->supplier));
        \Arr::set($data,"created",$this->created_user->name);
        \Arr::set($data,"status",["name"=>$this->status->name,"label"=>$this->status->label]);
        \Arr::set($data,"date",mysql_str_date($this->date_created));
        \Arr::set($data,"total",number_format($this->total,2));
        \Arr::set($data,"items", PurchaseOrderItemResource::collection($this->purchase_order_items));

        return $data;
    }
}

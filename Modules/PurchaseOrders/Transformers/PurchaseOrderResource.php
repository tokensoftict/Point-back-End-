<?php

namespace Modules\PurchaseOrders\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\AuthCollection;
use Modules\PurchaseOrders\Entities\PurchaseOrderItem;
use Modules\Settings\Transformers\SupplierResource;

class PurchaseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public static $columns = [
        "id",
        "Supplier",
        "Branch",
        "Created_By",
        "Status",
        "Date",
        "Total",
        "No_of_Items",
        "Action"
    ];

    public function toArray($request)
    {
        $data = [];


        \Arr::set($data,"id",$this->id);
        \Arr::set($data,"Branch",getBranch()->name);
        \Arr::set($data,"Supplier",$this->supplier->name);
        \Arr::set($data,"Created_By",$this->created_user->name);
        \Arr::set($data,"Status",["name"=>$this->status->name,"label"=>$this->status->label]);
        \Arr::set($data,"Date",mysql_str_date($this->date_created));
        \Arr::set($data,"Total",number_format($this->total,2));
        \Arr::set($data,"Total_",$this->total);
        \Arr::set($data,"No_of_Items", $this->purchase_order_items()->count());

        return $data;
    }
}

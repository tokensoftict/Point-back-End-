<?php

namespace Modules\InvoiceManager\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class InvoiceListResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public static $columns = [
        "No",
        "Invoice Number",
        "Customer",
        "Status",
        "Sub Total",
        "Date",
        "Time",
        "Total Items",
        "Created By",
        "Action"
    ];

    public function toArray($request)
    {
        $data = [];
        Arr::set($data,"Invoice Number",$this->invoice_number);
        Arr::set($data,"Customer",$this->customer->fullname);
        Arr::set($data,"Status",["label"=>$this->status->label, "name"=>$this->status->name]);
        Arr::set($data,"Sub Total",number_format($this->sub_total,2));
        Arr::set($data,"Date",eng_str_date($this->invoice_date));
        Arr::set($data,"Time",twelve_hour_time($this->sales_time));
        Arr::set($data,"Total Items",$this->invoice_items->count());
        Arr::set($data,"Created By",$this->create_user->name);

        $action = [];

        Arr::set($action,"View Invoice", $this->id."/show");

        if($this->status->name == "Draft")
        {
            Arr::set($action,"Edit Invoice", $this->id."/edit");
        }

        Arr::set($data,"action",$action);

        return $data;
    }
}

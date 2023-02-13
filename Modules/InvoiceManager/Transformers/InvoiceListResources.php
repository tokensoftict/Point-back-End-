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
        "Branch",
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
        Arr::set($data,"Branch",getBranch()->name);
        Arr::set($data,"ID",$this->id);
        Arr::set($data,"Customer",$this->customer->fullname);
        Arr::set($data,"Status",["label"=>$this->status->label, "name"=>$this->status->name]);
        Arr::set($data,"Sub Total",number_format($this->sub_total,2));
        Arr::set($data,"Sub Total_",$this->sub_total);
        Arr::set($data,"Date",eng_str_date($this->invoice_date));
        Arr::set($data,"Time",twelve_hour_time($this->sales_time));
        Arr::set($data,"Total Items",$this->invoice_items->count());
        Arr::set($data,"Created By",$this->create_user->name);

        $action = [];

        Arr::set($action,"View Invoice", ['type'=>'internal','permission'=>"/invoice/:id/show",'link'=>"/invoice/".$this->id."/show"]);

        Arr::set($action,"Print Invoice A4", ['type'=>'external','permission'=>"/invoice//printA4",'link'=>route("invoice.print_afour",$this->id)]);

        Arr::set($action,"Print Invoice Thermal", ['type'=>'external','permission'=>"/invoice//printThermal",'link'=>route("invoice.pos_print",$this->id)]);

        if($this->status->name == "Draft")
        {
            Arr::set($action,"Edit Invoice", ['type'=>'internal','permission'=>"/invoice/:id/edit",'link'=>'/invoice/'.$this->id."/edit"]);
        }

        Arr::set($data,"action",$action);

        return $data;
    }
}

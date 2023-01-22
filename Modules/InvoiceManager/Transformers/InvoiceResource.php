<?php

namespace Modules\InvoiceManager\Transformers;

use Arr;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\AuthCollection;
use Modules\CustomerManager\Transformers\CustomerManagerResource;

class InvoiceResource extends JsonResource
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

        Arr::set($data,"invoice_number",$this->invoice_number);
        Arr::set($data,"customer",new CustomerManagerResource($this->customer));
        Arr::set($data,"created_by",new AuthCollection($this->create_user));
        Arr::set($data,"last_updated",new AuthCollection($this->last_updated));
        Arr::set($data,"voided",new AuthCollection($this->voided));

        Arr::set($data,"status",["label"=>$this->status->label, "name"=>$this->status->name]);
        Arr::set($data,"sub_total",number_format($this->sub_total,2));
        Arr::set($data,"created_by",$this->create_user->name);
        Arr::set($data,"items_count",$this->invoice_items->count());
        Arr::set($data,"items",InvoiceItemResources::collection($this->invoice_items));
        Arr::set($data,"formatted_invoice_date",str_date2($this->invoice_date));
        Arr::set($data,"invoice_date",mysql_str_date($this->invoice_date));
        Arr::set($data,"sales_time",twelve_hour_time($this->sales_time));


        $action = [];

        Arr::set($action,"View Invoice", ['type'=>'internal','permission'=>"/invoice/:id/show",'link'=>$this->id."/show"]);

        Arr::set($action,"Print Invoice A4", ['type'=>'external','permission'=>"/invoice//printA4",'link'=>route("invoice.print_afour",$this->id)]);

        Arr::set($action,"Print Invoice Thermal", ['type'=>'external','permission'=>"/invoice//printThermal",'link'=>route("invoice.pos_print",$this->id)]);

        if($this->status->name == "Draft")
        {
            \Illuminate\Support\Arr::set($action,"Edit Invoice", ['type'=>'internal','permission'=>"/invoice/:id/edit",'link'=>$this->id."/edit"]);
        }

        Arr::set($data,"action",$action);


        return $data;
    }
}

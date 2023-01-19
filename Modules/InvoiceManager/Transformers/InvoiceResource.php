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

        Arr::set($action,"View Invoice", $this->id."/show");

        if($this->status->name == "Draft")
        {
            Arr::set($action,"Edit Invoice", $this->id."/edit");
        }

        Arr::set($data,"action",$action);


        return $data;
    }
}

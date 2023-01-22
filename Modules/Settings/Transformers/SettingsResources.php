<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResources extends JsonResource
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
        $data['date'] = dailyDate();
        $data['monthly'] = monthlyDateRange();
        $data['weekly'] = weeklyDateRange();
        return $data;
    }
}

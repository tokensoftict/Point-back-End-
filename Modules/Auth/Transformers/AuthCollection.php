<?php

namespace Modules\Auth\Transformers;

use App\Classes\Settings;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Settings\Transformers\SettingsResources;

class AuthCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request) : array
    {
        $data =  parent::toArray($request);
        Arr::set($data,"token",$this->user_token);
        Arr::set($data,"settings",new SettingsResources(collect(app()->make(Settings::class)->all())));
        return $data;
    }

    protected static function getSettings(Settings $settings) : array
    {
        return $settings->all();
    }
}

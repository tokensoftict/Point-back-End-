<?php


namespace Modules\Settings\Http\Controllers\Settings;

use App\Classes\Settings;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\Settings\Http\Requests\StoreSettingsRequest;


class StoreSettings extends Controller
{
    use RespondsWithHttpStatus;

    public function show(Settings $settings): JsonResponse
    {

        $store = $settings->get("store");

        if (!$store) $store = [];

        return $this->success("Data fetched", $store);
    }


    public function update(Settings $settings, Request $request): JsonResponse
    {

        if ($request->hasFile("logo")) {
            $fileName = time() . request()->file('logo')->getClientOriginalName();
            $path = 'photo/';
            Storage::disk('public')->put($path . $fileName, File::get(request()->file('logo')));

            $filePath = 'storage/' . $path . $fileName;

        }

        $data = $request->all();

        if(isset($filePath))
        {
            $data['logo'] = $filePath;
        }

        unset($data['_method']);

        $settings->put("store", $data);

        $store = $settings->get("store");

        if (!$store) $store = [];

        return $this->success("Data fetched", $store);
    }
}

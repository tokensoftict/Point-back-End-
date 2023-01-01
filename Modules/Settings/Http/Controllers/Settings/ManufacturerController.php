<?php

namespace Modules\Settings\Http\Controllers\Settings;


use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Entities\Manufacturer;
use Modules\Settings\Http\Requests\ManufacturerRequest;
use Modules\Settings\Transformers\ManufacturerResource;

class ManufacturerController extends Controller
{

    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", ManufacturerResource::collection(Manufacturer::all()));

    }


    public function create(){

    }


    public function edit(Manufacturer $manufacturer){

        return $this->success("Data fetched", new ManufacturerResource($manufacturer));

    }


    public function toggle(Manufacturer $manufacturer){

        $this->toggleState($manufacturer);

        return $this->success("Data fetched", new ManufacturerResource($manufacturer));

    }


    public function store(ManufacturerRequest $request){

        $manufacturer = Manufacturer::create($request->only(Manufacturer::$fields));

        return $this->success("Data fetched", new ManufacturerResource($manufacturer));

    }


    public function update(ManufacturerRequest $request, Manufacturer $manufacturer){

        $manufacturer->update($request->only(Manufacturer::$fields));

        return $this->success("Data fetched", new ManufacturerResource($manufacturer));
    }

    public function destroy(Manufacturer $manufacturer)
    {
        $manufacturer->delete();

        return $this->success("Data fetched", []);
    }
}

<?php

namespace Modules\BakeryManager\Http\Controllers\BakeryManager;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\BakeryManager\Entities\Materialtype;
use Modules\BakeryManager\Entities\Rawmaterial;
use Modules\BakeryManager\Http\Requests\RawmaterialRequest;
use Modules\BakeryManager\Transformers\MaterialFilterResource;
use Modules\BakeryManager\Transformers\MaterialTypeResource;
use Modules\BakeryManager\Transformers\RawMaterialResource;

class RawMaterialController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", RawMaterialResource::collection(Rawmaterial::all()));

    }


    public function find(Request $request)
    {
       return $this->success("Data fetched",MaterialFilterResource::collection(Rawmaterial::query()->filterMaterial($request->get("query"))->get()));
    }

    public function findAvailable(Request $request)
    {
       return $this->success("Data fetched",MaterialFilterResource::collection(Rawmaterial::query()->available()->filterMaterial($request->get("query"))->get()));
    }




    public function edit(Rawmaterial $rawmaterial){

        return $this->success("Data fetched", new RawMaterialResource($rawmaterial));

    }


    public function toggle(Rawmaterial $rawmaterial){

        $this->toggleState($rawmaterial);

        return $this->success("Data fetched", new RawMaterialResource($rawmaterial));

    }


    public function store(RawmaterialRequest $request){

        $rawmaterial = Rawmaterial::create($request->only(Rawmaterial::$fields));

        return $this->success("Data fetched", new RawMaterialResource($rawmaterial));

    }


    public function update(RawmaterialRequest $request, Rawmaterial $rawmaterial){

        $rawmaterial->update($request->only(Rawmaterial::$fields));

        return $this->success("Data fetched", new RawMaterialResource($rawmaterial));
    }

    public function destroy(Rawmaterial $rawmaterial)
    {
        $rawmaterial->delete();

        return $this->success("Data fetched", []);
    }

    public function listMaterialType()
    {
        return $this->success("Data fetched",MaterialTypeResource::collection(Materialtype::all()));
    }

}

<?php

namespace Modules\Settings\Http\Controllers\Settings;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Entities\Supplier;
use Modules\Settings\Http\Requests\SupplierRequest;
use Modules\Settings\Transformers\SupplierResource;

class SupplierController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", SupplierResource::collection(Supplier::all()));

    }


    public function create(){

    }


    public function edit(Supplier $supplier){

        return $this->success("Data fetched", new SupplierResource($supplier));

    }


    public function toggle(Supplier $supplier){

        $this->toggleState($supplier);

        return $this->success("Data fetched", new SupplierResource($supplier));

    }


    public function store(SupplierRequest $request){

        $supplier = Supplier::create($request->only(Supplier::$fields));

        return $this->success("Data fetched", new SupplierResource($supplier));

    }


    public function update(SupplierRequest $request, Supplier $supplier){

        $supplier->update($request->only(Supplier::$fields));

        return $this->success("Data fetched", new SupplierResource($supplier));
    }

    public function destroy( Supplier $supplier)
    {
        $supplier->delete();

        return $this->success("Data fetched", []);
    }
}

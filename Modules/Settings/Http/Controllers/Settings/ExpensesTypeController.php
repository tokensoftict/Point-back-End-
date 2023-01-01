<?php

namespace Modules\Settings\Http\Controllers\Settings;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Settings\Entities\ExpensesType;
use Modules\Settings\Http\Requests\ExpensesTypeRequest;
use Modules\Settings\Transformers\ExpensesTypeResource;

class ExpensesTypeController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", ExpensesTypeResource::collection(ExpensesType::all()));

    }


    public function create(){

    }


    public function edit(ExpensesType $expensesType){

        return $this->success("Data fetched", new ExpensesTypeResource($expensesType));

    }


    public function toggle(ExpensesType $expensesType){

        $this->toggleState($expensesType);

        return $this->success("Data fetched", new ExpensesTypeResource($expensesType));

    }


    public function store(ExpensesTypeRequest $request){

        $productCategory = ExpensesType::create($request->only(ExpensesType::$fields));

        return $this->success("Data fetched", new ExpensesTypeResource($productCategory));

    }


    public function update(ExpensesTypeRequest $request, ExpensesType $expensesType){

        $expensesType->update($request->only(ExpensesType::$fields));

        return $this->success("Data fetched", new ExpensesTypeResource($expensesType));
    }

    public function destroy(ExpensesType $expensesType)
    {
        $expensesType->delete();

        return $this->success("Data fetched", []);
    }
}

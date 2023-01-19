<?php

namespace Modules\CustomerManager\Http\Controllers\CustomerManager;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CustomerManager\Entities\Customer;
use Modules\CustomerManager\Http\Requests\CustomerManagerRequest;
use Modules\CustomerManager\Transformers\CustomerManagerResource;

class CustomerController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", CustomerManagerResource::collection(Customer::all()));

    }


    public function find(){

        return $this->success("Data fetched", CustomerManagerResource::collection(Customer::query()->filter(\request()->get("search"))->get()));

    }


    public function edit(Customer $customer){

        return $this->success("Data fetched", new CustomerManagerResource($customer));

    }


    public function toggle(Customer $customer){

        $this->toggleState($customer);

        return $this->success("Data fetched", new CustomerManagerResource($customer));

    }


    public function store(CustomerManagerRequest $request){

        $customer = Customer::create($request->only(Customer::$fields));

        return $this->success("Data fetched", new CustomerManagerResource($customer));

    }


    public function update(CustomerManagerRequest $request, Customer $customer){

        $customer->update($request->only(Customer::$fields));

        return $this->success("Data fetched", new CustomerManagerResource($customer));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return $this->success("Data fetched", []);
    }

}

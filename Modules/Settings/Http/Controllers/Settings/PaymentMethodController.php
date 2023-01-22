<?php

namespace Modules\Settings\Http\Controllers\Settings;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Entities\PaymentMethod;
use Modules\Settings\Http\Requests\PaymentMethodRequest;
use Modules\Settings\Transformers\PaymentMethodResource;

class PaymentMethodController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", PaymentMethodResource::collection(PaymentMethod::all()));

    }


    public function edit(PaymentMethod $paymentMethod){

        return $this->success("Data fetched", new PaymentMethodResource($paymentMethod));

    }


    public function toggle(PaymentMethod $paymentMethod){

        $this->toggleState($paymentMethod);

        return $this->success("Data fetched", new PaymentMethodResource($paymentMethod));

    }


    public function store(PaymentMethodRequest $request){

        $productCategory = PaymentMethod::create($request->only(PaymentMethod::$fields));

        return $this->success("Data fetched", new PaymentMethodResource($productCategory));

    }


    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod){

        $paymentMethod->update($request->only(PaymentMethod::$fields));

        return $this->success("Data fetched", new PaymentMethodResource($paymentMethod));
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return $this->success("Data fetched", []);
    }
}

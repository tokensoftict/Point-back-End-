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
use Modules\InvoiceManager\Entities\Invoice;
use Modules\PaymentManager\Entities\CreditPaymentLog;
use Modules\PaymentManager\Transformers\CustomerBalanceSheetResource;
use Modules\Settings\Entities\BankAccount;
use Modules\Settings\Entities\PaymentMethod;
use Modules\Settings\Transformers\BankAccountResource;
use Modules\Settings\Transformers\PaymentMethodResource;

class CustomerController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", CustomerManagerResource::collection(Customer::all()));

    }


    public function find() : JsonResponse{

        return $this->success("Data fetched", CustomerManagerResource::collection(Customer::query()->filter(\request()->get("search"))->get()));

    }


    public function edit(Customer $customer) : JsonResponse{

        return $this->success("Data fetched", new CustomerManagerResource($customer));

    }


    public function toggle(Customer $customer) : JsonResponse{

        $this->toggleState($customer);

        return $this->success("Data fetched", new CustomerManagerResource($customer));

    }


    public function store(CustomerManagerRequest $request) : JsonResponse{

        $customer = Customer::create($request->only(Customer::$fields));

        return $this->success("Data fetched", new CustomerManagerResource($customer));

    }


    public function update(CustomerManagerRequest $request, Customer $customer) : JsonResponse{

        $customer->update($request->only(Customer::$fields));

        return $this->success("Data fetched", new CustomerManagerResource($customer));
    }

    public function destroy(Customer $customer) : JsonResponse
    {
        $customer->delete();

        return $this->success("Data fetched", []);
    }


    public function findByphone(Request $request) : JsonResponse
    {
        $customer = Customer::where('phone_number',$request->get('phoneNumber'))->first();

        if(!$customer) return $this->success("Data fetched",["status"=>false,"message"=>"Customer Not Found, Please check and try again"]);

        return $this->success("Data fetched", [
            "status"=>true,
            "customer"=>new CustomerManagerResource($customer),
            "methods"=>PaymentMethodResource::collection(PaymentMethod::where("status",1)->get()),
            "banks" => BankAccountResource::collection(BankAccount::where("status",1)->get())
        ]);

    }


    public function balancesheet(Request $request) : JsonResponse
    {
        $data = json_decode($request->get('filter'),true);

        $data['opening'] = CreditPaymentLog::where('customer_id', $data['customer_id'])->where('payment_date','<', $data['between'][0])->sum('amount');

        $data['histories'] = CustomerBalanceSheetResource::collection(
            CreditPaymentLog::where('customer_id', $data['customer_id'])->whereBetween('payment_date',$data['between'])->get()
        );

        $data['columns'] =CustomerBalanceSheetResource::$columns;


        return $this->success("Data fetched",$data);
    }

}

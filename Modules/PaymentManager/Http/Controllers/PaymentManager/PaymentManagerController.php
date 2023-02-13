<?php

namespace Modules\PaymentManager\Http\Controllers\PaymentManager;

use App\Models\Status;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InvoiceManager\Entities\Invoice;
use Modules\InvoiceManager\Transformers\InvoiceListResources;
use Modules\PaymentManager\Entities\CreditPaymentLog;
use Modules\PaymentManager\Entities\Payment;
use Modules\PaymentManager\Entities\PaymentMethodTable;
use Modules\PaymentManager\Http\Requests\PaymentRequest;
use Modules\PaymentManager\Transformers\PaymentListResource;
use Modules\PaymentManager\Transformers\PaymentMethodListResources;
use Modules\Settings\Entities\BankAccount;
use Modules\Settings\Entities\PaymentMethod;
use Modules\Settings\Transformers\BankAccountResource;
use Modules\Settings\Transformers\PaymentMethodResource;

class PaymentManagerController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse
    {
        return $this->success("Data fetched",
           [
               "columns" => PaymentListResource::$columns,
               "data" =>  PaymentListResource::collection(Payment::query()->where('branch_id',getBranch()->id)->today()->get())
           ]
        );
    }


    public function storeCreditPayment(PaymentRequest $request): JsonResponse
    {
       $log =  CreditPaymentLog::create(
            [
                'user_id' => auth()->id(),
                'payment_method_id' => $request->get("payment_method"),
                'customer_id' => $request->get('customer_id'),
                'invoice_number' => time(),
                //'invoice_id' => NULL,
                'amount' => $request->get('amount'),
                'payment_date' => dailyDate(),
                'payment_id' => NULL
            ]
        );

       $payment = Payment::createPayment($request,$log,CreditPaymentLog::class);

        $log->payment_id = $payment->id;
        $log->save();

       return $this->success("Data fetched", ['status'=>true,'data'=>new PaymentListResource($payment)]);
    }

    public function store(PaymentRequest $request) : JsonResponse
    {
        $invoice = Invoice::find($request->get("invoice_id"));

        if(!$invoice)  return $this->success("Data fetched", ['status'=>false,'message'=>"Unknown error Unable to locate this invoice"]);

        $payment = Payment::createPayment($request,$invoice,Invoice::class);

        return $this->success("Data fetched", ['status'=>true,'data'=>new PaymentListResource($payment)]);

    }


    public function invoice($id)
    {
        $id = (int)$id;

        $invoice = Invoice::find($id);

        if(!$invoice) $invoice = Invoice::where("invoice_number",$id)->first();

        if(!$invoice) return $this->success("Data fetched",["status"=>false,"message"=>"Invoice Not Found, Please check and try again"]);

        if($invoice->status_id ==  Status::where("name","complete")->first()->id) return $this->success("Data fetched",["status"=>false,"message"=>"Invoice has already been paid"]);

        return $this->success("Data fetched", [
            "status"=>true,
            "invoice"=>new InvoiceListResources($invoice),
            "methods"=>PaymentMethodResource::collection(PaymentMethod::where("status",1)->get()),
            "banks" => BankAccountResource::collection(BankAccount::where("status",1)->get())
        ]);
    }


    public function payment_by_method()
    {
        return $this->success(
            "Data fetched",
            [
                "columns" => PaymentMethodListResources::$columns,
                "data" => PaymentMethodTable::query()->where('branch_id',getBranch()->id)->today()->ignorecredit()->methodWise('payment_method_id',PaymentMethodListResources::class)
            ]
        );
    }

    public function payment_by_method_custom()
    {
        return $this->success(
            "Data fetched",
            [
                "columns" => PaymentMethodListResources::$columns,
                "data" => PaymentMethodTable::query()->where('branch_id',getBranch()->id)->filterdata()->ignorecredit()->methodWise('payment_method_id',PaymentMethodListResources::class)
            ]
        );
    }


    public function custom()
    {
        return $this->success("Data fetched",
            [
                "columns" => PaymentListResource::$columns,
                "data" =>  PaymentListResource::collection(Payment::query()->where('branch_id',getBranch()->id)->filterdata()->get())
            ]
        );
    }




}

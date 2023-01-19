<?php

namespace Modules\InvoiceManager\Http\Controllers\InvoiceManager;


use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InvoiceManager\Entities\Invoice;
use Modules\InvoiceManager\Http\Requests\InvoiceRequest;
use Modules\InvoiceManager\Transformers\InvoiceListResources;
use Modules\InvoiceManager\Transformers\InvoiceResource;


class InvoiceManagerController extends Controller
{

    use RespondsWithHttpStatus;

    public function index() : JsonResponse
    {

        return $this->success("Data fetched",
            [
                "columns" => InvoiceListResources::$columns,
                "data" =>InvoiceListResources::collection(Invoice::query()->today()->get())
            ]
            );

    }


    public function draft() : JsonResponse
    {

        return $this->success("Data fetched",
            [
                "columns" => InvoiceListResources::$columns,
                "data" =>InvoiceListResources::collection(InvoiceListResources::collection(Invoice::query()->today()->draft()->get()))
            ]
        );


    }

    public function complete() : JsonResponse
    {

        return $this->success("Data fetched",
            [
                "columns" => InvoiceListResources::$columns,
                "data" =>InvoiceListResources::collection(InvoiceListResources::collection(Invoice::query()->today()->complete()->get()))
            ]
        );

    }

    public function store(InvoiceRequest $request) : JsonResponse
    {
        $invoice = Invoice::createInvoice($request);

        if(is_array($invoice)) return $this->success("error",["status"=>false,"error"=>$invoice]);

        return $this->success("Data fetched", ["status"=>true,"data"=> new InvoiceResource($invoice)]);

    }


    public function update(InvoiceRequest $request, Invoice $invoice) : JsonResponse
    {

        $invoice = Invoice::updateInvoice($request, $invoice);

        if(is_array($invoice)) return $this->success("error",["status"=>false,"error"=>$invoice]);

        return $this->success("Data fetched", ["status"=>true,"data"=> new InvoiceResource($invoice)]);
    }


    public function show(Invoice $invoice)
    {
        return $this->success("Data fetched", new InvoiceResource($invoice));
    }


    public function destroy(Request $request,Invoice $invoice) : JsonResponse
    {
        $invoice->void($request);

        return $this->success("Data fetched", []);
    }
}

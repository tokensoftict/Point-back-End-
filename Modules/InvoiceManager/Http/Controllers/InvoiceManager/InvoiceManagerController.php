<?php

namespace Modules\InvoiceManager\Http\Controllers\InvoiceManager;


use App\Classes\Settings;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InvoiceManager\Entities\Invoice;
use Modules\InvoiceManager\Http\Requests\InvoiceRequest;
use Modules\InvoiceManager\Transformers\InvoiceListResources;
use Modules\InvoiceManager\Transformers\InvoiceResource;
use Modules\PaymentManager\Entities\Payment;
use Modules\PaymentManager\Entities\PaymentMethodTable;
use PDF;

class InvoiceManagerController extends Controller
{

    use RespondsWithHttpStatus;

    protected $settings;

    public function __construct(Settings $_settings){
        $this->settings = $_settings;
    }

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


    public function dailyreport()
    {
        return $this->success("Data fetched",
            [
                "columns" => InvoiceListResources::$columns,
                "data" =>InvoiceListResources::collection(Invoice::query()->filterdata()->get())
            ]
        );
    }


    public function print_pos($id){
        $data = [];
        $invoice = Invoice::with(['create_user','customer','invoice_items'])->findorfail($id);
        $invoice->payment = Payment::where('invoice_id',$id)->where("invoice_type",Invoice::class);
        $invoice->paymentMethodTable = PaymentMethodTable::where('invoice_id',$id)->where("invoice_type",Invoice::class);
        $data['invoice'] =$invoice;
        $data['store'] =  $this->settings->store();
        $page_size = $invoice->invoice_items()->get()->count() * 15;
        $page_size += 180;
        //return view('print.pos', $data);
        $pdf = PDF::loadView('print.pos', $data,[],[
            'format' => [80,$page_size],
            'margin_left'          => 0,
            'margin_right'         => 0,
            'margin_top'           => 0,
            'margin_bottom'        => 0,
            'margin_header'        => 0,
            'margin_footer'        => 0,
            'orientation'          => 'P',
            'display_mode'         => 'fullpage',
            'custom_font_dir'      => '',
            'custom_font_data' 	   => [],
            'default_font_size'    => '12',
        ]);

        return $pdf->stream('document.pdf');
    }

    public function print_afour($id){
        $data = [];
        $invoice = Invoice::with(['create_user','customer','invoice_items'])->findorfail($id);
        $invoice->payment = Payment::where('invoice_id',$id)->where("invoice_type",Invoice::class);
        $invoice->paymentMethodTable = PaymentMethodTable::where('invoice_id',$id)->where("invoice_type",Invoice::class);
        $data['invoice'] = $invoice;
        $data['store'] =  $this->settings->store();
        $pdf = PDF::loadView("print.pos_afour",$data);
        return $pdf->stream('document.pdf');
    }

    public function print_way_bill($id){
        $data = [];
        $invoice = Invoice::with(['created_by','customer','invoice_items'])->findorfail($id);
        $invoice->payment = Payment::where('invoice_id',$id);
        $invoice->paymentMethodTable = PaymentMethodTable::where('invoice_id',$id);
        $data['invoice'] = $invoice;
        $data['store'] =  $this->settings->store();
        $pdf = PDF::loadView("print.pos_afour_waybill",$data);
        return $pdf->stream('document.pdf');
    }

}

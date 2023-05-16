<?php

namespace Modules\InvoiceManager\Http\Controllers\InvoiceManager;


use App\Classes\Settings;
use App\Models\Status;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\InvoiceManager\Entities\Invoice;
use Modules\InvoiceManager\Entities\InvoiceItem;
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
                "data" =>InvoiceListResources::collection(Invoice::query()->where('branch_id',getBranch()->id)->today()->get())
            ]
            );

    }


    public function draft() : JsonResponse
    {

        return $this->success("Data fetched",
            [
                "columns" => InvoiceListResources::$columns,
                "data" =>InvoiceListResources::collection(InvoiceListResources::collection(Invoice::query()->where('branch_id',getBranch()->id)->today()->draft()->get()))
            ]
        );


    }

    public function complete() : JsonResponse
    {

        return $this->success("Data fetched",
            [
                "columns" => InvoiceListResources::$columns,
                "data" =>InvoiceListResources::collection(InvoiceListResources::collection(Invoice::query()->where('branch_id',getBranch()->id)->today()->complete()->get()))
            ]
        );

    }

    public function paid() : JsonResponse
    {

        return $this->success("Data fetched",
            [
                "columns" => InvoiceListResources::$columns,
                "data" =>InvoiceListResources::collection(InvoiceListResources::collection(Invoice::query()->where('branch_id',getBranch()->id)->today()->paid()->get()))
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
                "data" =>InvoiceListResources::collection(Invoice::query()->where('branch_id',getBranch()->id)->filterdata()->get())
            ]
        );
    }


    public function print_pos($id)
    {
        $data = [];
        $invoice = Invoice::with(['create_user','customer','invoice_items'])->findorfail($id);
        $invoice->payment = Payment::where('invoice_id',$id)->where("invoice_type",Invoice::class)->first();
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

    public function print_afour($id)
    {
        $data = [];
        $invoice = Invoice::with(['create_user','customer','invoice_items'])->findorfail($id);
        $invoice->payment = Payment::where('invoice_id',$id)->where("invoice_type",Invoice::class)->first();
        $invoice->paymentMethodTable = PaymentMethodTable::where('invoice_id',$id)->where("invoice_type",Invoice::class);
        $data['invoice'] = $invoice;
        $data['store'] =  $this->settings->store();
        $pdf = PDF::loadView("print.pos_afour",$data);
        return $pdf->stream('document.pdf');
    }

    public function print_way_bill($id)
    {
        $data = [];
        $invoice = Invoice::with(['created_by','customer','invoice_items'])->findorfail($id);
        $invoice->payment = Payment::where('invoice_id',$id)->where("invoice_type",Invoice::class)->first();
        $invoice->paymentMethodTable = PaymentMethodTable::where('invoice_id',$id);
        $data['invoice'] = $invoice;
        $data['store'] =  $this->settings->store();
        $pdf = PDF::loadView("print.pos_afour_waybill",$data);
        return $pdf->stream('document.pdf');
    }


    public function completeInvoice(Invoice $invoice) : JsonResponse
    {
        $status = $invoice->complete();

        if(is_array($status)) return $this->success("Success",['status'=>false,"errors"=>$status]);

        return $this->success("Success",['status'=>true,'invoice'=> new InvoiceResource($invoice)]);
    }


    public function by_product(Request $request)
    {
        $filter = json_decode(request()->get("filter"),true);
        $data = $filter['between'];
        $status = $filter['status'];

        $lists = InvoiceItem::query()->select(
            'invoice_items.stock_id',
           DB::raw( 'stocks.name as `Product Name`'),
            DB::raw( 'SUM(invoice_items.quantity) as `Total Qty Sold`'),
            DB::raw( 'SUM(invoice_items.quantity * (invoice_items.selling_price - invoice_items.cost_price)) as total_profit'),
            DB::raw( 'SUM(invoice_items.quantity * (invoice_items.cost_price)) as `Total Cost Price`'),
            DB::raw( 'SUM(invoice_items.quantity * (invoice_items.selling_price)) as `Total Selling Price`')
        )
            ->whereHas('invoice',function($q) use(&$data, &$status){
            $q
                ->whereBetween('invoice_date',[$data[0],$data[1]])
                ->where(function($qq) use(&$data, &$status){
                    if($status == "Paid")
                    {
                        $qq->orWhere("status_id", Status::where("name","Paid")->first()->id);
                        $qq->orWhere("status_id", Status::where("name","Complete")->first()->id);
                    }

                    if($status == "Draft")
                    {
                        $qq->orwhere("status_id", Status::where("name","Draft")->first()->id);
                    }

                })->where('branch_id',getBranch()->id);
        })
            ->leftJoin('stocks', function($join){
                $join->on('stock_id', '=', 'stocks.id');
            })
            ->groupBy('invoice_items.stock_id')
            ->groupBy('stocks.name')
            ->get()->map(function($item){
                $item['total_profit'] = number_format($item['total_profit'],2);
                $item['Total Cost Price'] = number_format($item['Total Cost Price'],2);
                $item['Total Selling Price'] = number_format($item['Total Selling Price'],2);
                return $item;
            });
            $data = $lists->count() > 0 ? $lists->toArray() : [];
        return $this->success('data fetched', ['data'=>$data]);
    }

}

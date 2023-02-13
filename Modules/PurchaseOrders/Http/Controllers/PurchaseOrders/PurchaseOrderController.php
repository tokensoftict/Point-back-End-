<?php

namespace Modules\PurchaseOrders\Http\Controllers\PurchaseOrders;

use App\Traits\RespondsWithHttpStatus;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PurchaseOrders\Entities\PurchaseOrder;
use Modules\PurchaseOrders\Http\Requests\PurchaseOrderRequest;
use Modules\PurchaseOrders\Transformers\PurchaseOrderResource;
use Modules\PurchaseOrders\Transformers\PurchaseOrderWithItemResources;

class PurchaseOrderController extends Controller
{
   use RespondsWithHttpStatus;

    public function index(Request $request){

        return $this->success(
            "Data fetched",
         [
             "columns" => PurchaseOrder::$tableColumn,
             "data" => PurchaseOrderResource::collection(
                 PurchaseOrder::with(['supplier','created_user','user','status'])
                     ->where('branch_id',getBranch()->id)
                     ->where("type",$request->get("type"))
                         ->where("date_created",dailyDate())
                         ->orderBy("id","DESC")->get()
             )
         ]
        );

    }


    public function store(PurchaseOrderRequest $request): JsonResponse{

        return $this->success("Purchase created successful",
            new PurchaseOrderResource(PurchaseOrder::savePurchaseOrder($request)));

    }

    public function show(PurchaseOrder $purchaseOrder) : JsonResponse{

        return $this->success("Data fetched",new PurchaseOrderWithItemResources($purchaseOrder));
    }

    public function destroy(PurchaseOrder $purchaseOrder) : JsonResponse {

        $purchaseOrder->delete();

        return $this->success("Data fetched",['status'=>true]);
    }


    public function update(PurchaseOrder $purchaseOrder, PurchaseOrderRequest $request)
    {

        return $this->success("Purchase updated successful",
            new PurchaseOrderResource(PurchaseOrder::savePurchaseOrder($request,$purchaseOrder)));

    }

    public function markAsComplete(PurchaseOrder $purchaseOrder) : JsonResponse{

        $purchaseOrder =  $purchaseOrder->complete();

        return $this->success("Data fetched",new PurchaseOrderResource($purchaseOrder));

    }


    public function custom()
    {
        return $this->success("Data fetched",
            [
                "columns" => PurchaseOrderResource::$columns,
                "data" =>  PurchaseOrderResource::collection(PurchaseOrder::query()->where('branch_id',getBranch()->id)->filterdata()->get())
            ]
        );
    }

}

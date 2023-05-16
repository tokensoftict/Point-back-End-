<?php

namespace Modules\BakeryManager\Http\Controllers\BakeryManager;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BakeryManager\Entities\Bakeryproduction;
use Modules\BakeryManager\Entities\Bakeryproductionlog;
use Modules\BakeryManager\Http\Requests\ProductionRequest;
use Modules\BakeryManager\Transformers\BakeryProductionListResources;
use Modules\BakeryManager\Transformers\BakeryProductionResource;
use Modules\StockModule\Entities\Stock;


class ProductionController extends Controller
{

    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched",
            ['columns'=>BakeryProductionListResources::$colunm,"data"=> BakeryProductionListResources::collection(Bakeryproduction::where("production_date",dailyDate())->where('branch_id',getBranch()->id)->get())]
        );

    }


    public function store(ProductionRequest $request){

        return $this->success("Data fetched", new BakeryProductionResource(Bakeryproduction::saveProduction($request)));
    }


    public function show(Bakeryproduction $bakeryproduction){

        return $this->success("Data fetched", new BakeryProductionResource($bakeryproduction));

    }


    public function update(ProductionRequest $request, Bakeryproduction $bakeryproduction){

        return $this->success("Data fetched", new BakeryProductionResource(Bakeryproduction::saveProduction($request,$bakeryproduction)));
    }


    public function destroy(Bakeryproduction $bakeryproduction)
    {
        $bakeryproduction->delete();

        return $this->success("Data fetched", []);
    }


    public function complete(Bakeryproduction $bakeryproduction)
    {
        $bakeryproduction->complete();

        return $this->success("Data fetched", new BakeryProductionResource($bakeryproduction));
    }


    public function custom()
    {
        return $this->success("Data fetched",
            [
                "columns" => BakeryProductionListResources::$colunm,
                "data" =>  BakeryProductionListResources::collection(Bakeryproduction::query()->where('branch_id',getBranch()->id)->filterdata()->get())
            ]
        );
    }


    public function logproduction(Bakeryproduction $bakeryproduction, Stock $stock, Request $request)
    {
        $bakeryproduction->bakery_production_logs()->save(new Bakeryproductionlog([
            'stock_id' => $stock->id,
            'rough' => $request->get('rough'),
            'bags' => $request->get('bags'),
            'quantity' => $request->get('quantity'),
            'user_id' => auth()->id()
        ]));

        foreach ($bakeryproduction->bakery_production_products_items()->where('stock_id', $stock->id)->get() as $item){
            $item->quantity = $bakeryproduction->bakery_production_logs()->where('stock_id', $stock->id)->sum('quantity');
            $item->total = $item->quantity * $item->selling_price;
            $item->update();
        }

        return $this->success("Data fetched", new BakeryProductionResource($bakeryproduction));
    }


    public function viewproduction(Bakeryproduction $bakeryproduction, Stock $stock)
    {
        $logs = $bakeryproduction->bakery_production_logs()->with(['stock'])->where('stock_id', $stock->id)->get();

        return $this->success("Data fetched", ['data' => $logs->toArray()]);
    }


    public function destroyLog(Bakeryproductionlog $bakeryproductionlog)
    {
        $production_id = $bakeryproductionlog->bakeryproduction_id;

        $bakeryproductionlog->delete();

        $production  = Bakeryproduction::with(['bakery_production_logs','bakery_production_material_items','bakery_production_products_items'])->find($production_id);

        foreach ($production->bakery_production_products_items()->get() as $item){

            $item->quantity = $production->bakery_production_logs()->where('stock_id', $item->stock_id)->sum('quantity');

            $item->total = $item->quantity * $item->selling_price;

            $item->update();
        }

        return $this->success("Data fetched", [
            'production' => new BakeryProductionResource($production),
        ]);
    }

}

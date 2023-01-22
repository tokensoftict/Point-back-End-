<?php

namespace Modules\BakeryManager\Http\Controllers\BakeryManager;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\BakeryManager\Entities\Bakeryproduction;
use Modules\BakeryManager\Http\Requests\ProductionRequest;
use Modules\BakeryManager\Transformers\BakeryProductionListResources;
use Modules\BakeryManager\Transformers\BakeryProductionResource;


class ProductionController extends Controller
{

    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched",
            ['columns'=>BakeryProductionListResources::$colunm,"data"=> BakeryProductionListResources::collection(Bakeryproduction::where("production_date",dailyDate())->get())]
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
                "data" =>  BakeryProductionListResources::collection(Bakeryproduction::query()->filterdata()->get())
            ]
        );
    }

}

<?php

namespace Modules\StockModule\Http\Controllers\StockManager;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\StockModule\Entities\Stock;
use Modules\StockModule\Http\Requests\StockRequest;
use Modules\StockModule\Transformers\StockFilterResource;
use Modules\StockModule\Transformers\StockListAvailableResource;
use Modules\StockModule\Transformers\StockListResource;
use Modules\StockModule\Transformers\StockResource;

class StockController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse
    {
        return $this->success("Data fetched",['columns'=>StockListResource::$coloumns,"data"=>StockListResource::collection(Stock::query()->quickWith()->enabled()->get())]);
    }


    public function available() : JsonResponse
    {
        return $this->success("Data fetched",['columns'=>StockListAvailableResource::$coloumns,"data"=>StockListAvailableResource::collection(Stock::query()->quickWith()->available()->get())]);
    }


    public function disabled() : JsonResponse
    {
        return $this->success("Data fetched",['columns'=>StockListResource::$coloumns,"data"=>StockListResource::collection(Stock::query()->quickWith()->disabled()->get())]);

    }

    public function show(Stock $stock)
    {
        return  $this->success("Data fetched",new StockResource($stock));
    }

    public function store(StockRequest $request) : JsonResponse
    {
        $stock = Stock::saveStock($request);

        return  $this->success("Data fetched",new StockResource($stock));
    }


    public function update(StockRequest $request, Stock $stock) : JsonResponse
    {
        $stock = Stock::saveStock($request,$stock);

        return  $this->success("Data fetched",new StockResource($stock));
    }


    public function requesites() : JsonResponse
    {
        return $this->success("Data fetched",Stock::Requesites());
    }



    public function find(Request $request) : JsonResponse
    {
        return $this->success($request->get("query"), StockFilterResource::collection(
           Stock::query()->filterStock($request->get("query"))->get()
        ));
    }


    public function findAvailable(Request $request) : JsonResponse
    {
        return $this->success("Data fetched", StockFilterResource::collection(
            Stock::query()->filterAvailableStock($request->get("query"))->get()
        ));
    }

    public function toggle(Stock $stock)
    {
        $this->toggleState($stock);
        return  $this->success("Data fetched",new StockResource($stock));
    }

}

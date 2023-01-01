<?php

namespace Modules\BakeryManager\Http\Controllers\BakeryManager;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BakeryManager\Entities\Bakeryproduction;
use Modules\BakeryManager\Transformers\BakeryProductionResource;
use Modules\BakeryManager\Transformers\MaterialTransferResource;

class MaterialTransferController extends Controller
{
    use RespondsWithHttpStatus;

    public function index()
    {
        return $this->success("Data fetched",
            ['columns'=>MaterialTransferResource::$colunm,"data"=> MaterialTransferResource::collection(Bakeryproduction::where("production_date",dailyDate())->get())]
        );
    }


    public function show(Bakeryproduction $bakeryproduction)
    {
        return $this->success("Data fetched",new BakeryProductionResource($bakeryproduction));
    }


}

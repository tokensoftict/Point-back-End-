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

    public function index(Request $request)
    {
        return $this->success("Data fetched",
            ['columns'=>MaterialTransferResource::$colunm,"data"=> MaterialTransferResource::collection(Bakeryproduction::query()
                ->where('branch_id',getBranch()->id)
                ->filterdata()->get())]
        );
    }


    public function show(Bakeryproduction $bakeryproduction)
    {
        return $this->success("Data fetched",new BakeryProductionResource($bakeryproduction));
    }


    public function accept(Bakeryproduction $bakeryproduction)
    {
        $bakeryproduction->approve();
        return $this->success("Data fetched",new BakeryProductionResource($bakeryproduction));
    }


    public function decline(Bakeryproduction $bakeryproduction)
    {
        $bakeryproduction->decline();
        return $this->success("Data fetched",new BakeryProductionResource($bakeryproduction));
    }
}

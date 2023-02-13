<?php

namespace Modules\Settings\Http\Controllers\Settings;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Entities\Branch;
use Modules\Settings\Http\Requests\BranchRequest;
use Modules\Settings\Transformers\BranchResource;

class BranchController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse {

        return $this->success("Data fetched" ,BranchResource::collection(Branch::all()));
    }


    public function edit(Branch $branch) : JsonResponse{

        return $this->success("Data fetched", new BranchResource($branch));
    }


    public function store(BranchRequest $request){

        $branch = Branch::create($request->only(Branch::$fields));

        return $this->success("Data fetched", new BranchResource($branch));

    }


    public function update(BranchRequest $request, Branch $branch){

        $branch->update($request->only(Branch::$fields));

        return $this->success("Data fetched", new BranchResource($branch));
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return $this->success("Data fetched", []);
    }
}

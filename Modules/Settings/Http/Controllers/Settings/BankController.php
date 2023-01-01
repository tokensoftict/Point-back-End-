<?php

namespace Modules\Settings\Http\Controllers\Settings;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Entities\Bank;
use Modules\Settings\Entities\BankAccount;
use Modules\Settings\Http\Requests\BankRequest;
use Modules\Settings\Transformers\BankAccountResource;
use Modules\Settings\Transformers\BankResource;

class BankController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", BankAccountResource::collection(BankAccount::all()));

    }


    public function create(){

    }


    public function edit(BankAccount $bankAccount){

        return $this->success("Data fetched", new BankAccountResource($bankAccount));

    }


    public function toggle(BankAccount $bankAccount){

        $this->toggleState($bankAccount);

        return $this->success("Data fetched", new BankAccountResource($bankAccount));

    }


    public function store(BankRequest $request){

        $productCategory = BankAccount::create($request->only(BankAccount::$fields));

        return $this->success("Data fetched", new BankAccountResource($productCategory));

    }


    public function update(BankRequest $request, BankAccount $bankAccount){

        $bankAccount->update($request->only(BankAccount::$fields));

        return $this->success("Data fetched", new BankAccountResource($bankAccount));
    }

    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();

        return $this->success("Data fetched", []);
    }

    public function listAllCommercial()
    {
        return $this->success("Data fetched",BankResource::collection(Bank::all()));
    }
}

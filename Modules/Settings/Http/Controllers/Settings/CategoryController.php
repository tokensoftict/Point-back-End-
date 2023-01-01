<?php

namespace Modules\Settings\Http\Controllers\Settings;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Entities\Category;
use Modules\Settings\Http\Requests\CategoryRequest;
use Modules\Settings\Transformers\CategoryResource;

class CategoryController extends Controller
{
    use RespondsWithHttpStatus;

    public function index() : JsonResponse{

        return $this->success("Data fetched", CategoryResource::collection(Category::all()));

    }


    public function create(){

    }


    public function edit(Category $productCategory){

        return $this->success("Data fetched", new CategoryResource($productCategory));

    }


    public function toggle(Category $productCategory){

        $this->toggleState($productCategory);

        return $this->success("Data fetched", new CategoryResource($productCategory));

    }


    public function store(CategoryRequest $request){

        $productCategory = Category::create($request->only(Category::$fields));

        return $this->success("Data fetched", new CategoryResource($productCategory));

    }


    public function update(CategoryRequest $request, Category $productCategory){

        $productCategory->update($request->only(Category::$fields));

        return $this->success("Data fetched", new CategoryResource($productCategory));
    }

    public function destroy(Category $productCategory)
    {
        $productCategory->delete();

        return $this->success("Data fetched", []);
    }
}

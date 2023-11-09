<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\MainProductCategory;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function categories()
    {
        try {
        $categories=CategoryResource::collection(MainProductCategory::all());
        return response()->json($categories,200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Categories not found'], 404);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function category(string $id)
    {
        try {
        $category=new CategoryResource(MainProductCategory::where('slug',$id)->firstOrFail());
            return response()->json($category,200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Category not found'], 404);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

}

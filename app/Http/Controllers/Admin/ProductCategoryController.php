<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainProductCategory;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Datatables;
class ProductCategoryController extends Controller
{


    public $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    private function authorizeUser($permission,$type): void
    {
        if (is_null($this->user) || !$this->user->can($permission.'.'.$type)) {
            abort(403, 'Sorry !! You are Unauthorized!');
        }
    }

    /**
 * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeUser('category','view');
        $productCategories = ProductCategory::with('parent')->get();
        // dd($productCategories);

        if($request->ajax()){
            $data = ProductCategory::latest()->get();
            return Datatables::of($data)
                ->make(true);
        }
        else{
            return view('admin.dashboard.category.index', ['productCategories'=>$productCategories]);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeUser('category','create');
        $categories = MainProductCategory::all();
        return view('admin.dashboard.category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeUser('category','create');

        $validatedData = $request->validate([

            'name' => 'required|unique:main_product_categories,name',
            'parent' => 'nullable|exists:main_product_categories,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255'
        ]);
            // Handle logo upload
            // if ($request->hasFile('logo')) {
            //     $validatedData['logo'] = $request->file('logo')->store('main_product_categories');
            // }

            $category = new ProductCategory();
            $category->name = strtoupper($validatedData['name']);
            $category->parent_id = $validatedData['parent'] ?? null;
            $category->logo = $validatedData['logo'] ?? null;
            $category->featured = $validatedData['featured'] ?? false;
            $category->status = $validatedData['status'] ?? true;
            $category->meta_title = $validatedData['meta_title'] ?? null;
            $category->meta_description = $validatedData['meta_description'] ?? null;
            $category->meta_keyword = $validatedData['meta_keyword'] ?? null;
            $category->save();

            return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorizeUser('category','view');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizeUser('category','edit');
        $categories = MainProductCategory::all();
        $category = ProductCategory::findOrfail($id);
        return view('admin.dashboard.category.edit',compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeUser('category','edit');
        $category = ProductCategory::findOrFail($id);
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_categories')->ignore($id),
            ],
            'parent' => 'nullable|exists:main_product_categories,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255'
        ]);
            // Handle logo upload
            // if ($request->hasFile('logo')) {
            //     $validatedData['logo'] = $request->file('logo')->store('main_product_categories');
            // }

            $category->name = strtoupper($validatedData['name']);
            $category->parent_id = $validatedData['parent'] ?? null;
            $category->logo = $validatedData['logo'] ?? null;
            $category->featured = $validatedData['featured'] ?? false;
            $category->status = $validatedData['status'] ?? true;
            $category->meta_title = $validatedData['meta_title'] ?? null;
            $category->meta_description = $validatedData['meta_description'] ?? null;
            $category->meta_keyword = $validatedData['meta_keyword'] ?? null;
            $category->save();

            return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeUser('category','delete');
        try {
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Product Category ' . $productCategory->name . ' deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
}

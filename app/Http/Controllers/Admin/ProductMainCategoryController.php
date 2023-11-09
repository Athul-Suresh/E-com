<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductMainCategoryController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    private function authorizeUser($permission, $type): void
    {
        if (is_null($this->user) || !$this->user->can($permission . '.' . $type)) {
            abort(403, 'Sorry !! You are Unauthorized!');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorizeUser('category', 'view');
        $mainCategory = MainProductCategory::all();
        return view('admin.dashboard.category.mainIndex', compact('mainCategory'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $this->authorizeUser('category', 'create');
        return view('admin.dashboard.category.mainCreate');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response **/

    public function store(Request $request)
    {

        $this->authorizeUser('category', 'create');

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name'),
            ],
        ], [
            'name.required' => 'The Category Name field is required.',
            'name.string' => 'The Category Name field must be a string.',
            'name.max' => 'The Category Name may not be greater than :max characters.',
            'name.unique' => 'The Category Name has already been taken.',
            ]);

        try {
            $mainCategory = new MainProductCategory();
            $mainCategory->name = strtoupper($validatedData['name']);
            $mainCategory->save();

            return redirect()->route('admin.maincategories.index')->with('success', 'Category ' . $mainCategory->name . ' created successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.maincategories.index')->with('error', 'Category creation failed. ' . $e->getMessage());
        }
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorizeUser('category', 'edit');
        $category = MainProductCategory::findOrfail($id);
        // $mainCategory = MainProductCategory::all();
        return view('admin.dashboard.category.mainEdit', compact('category'));
    }

    public function update(Request $request,$id)
    {
        $this->authorizeUser('category', 'edit');

        try {
            $mainCategory = MainProductCategory::findOrFail($id);

            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('main_product_categories')->ignore($id),
                ]

            ], [
                'name.required' => 'The Main Category name field is required.',
                'name.string' => 'The Main Category name field must be a string.',
                'name.max' => 'The Main Category name may not be greater than :max characters.',
                'name.unique' => 'The Main Category name has already been taken.',
            ]);

            $mainCategory->name = strtoupper($request->input('name'));
            $mainCategory->save();
            return redirect()->route('admin.maincategories.index')->with('success', 'Main Category ' . $mainCategory->name . ' Updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeUser('category', 'delete');

        try {
            $mainCategory = MainProductCategory::findOrFail($id);
            $mainCategory->delete();
            return redirect()->route('admin.maincategories.index')->with('success', 'Brand ' . $mainCategory->name . ' deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $this->authorizeUser('category', 'edit');
        try {
            $mainCategory = MainProductCategory::findOrFail($id);
            $mainCategory->status == 1 ? $mainCategory->status = 0 : $mainCategory->status = 1;
            $mainCategory->save();
            return response()->json(['success' => true, 'status' => $mainCategory->status]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

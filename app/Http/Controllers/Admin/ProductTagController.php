<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductTag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use DataTables;

class ProductTagController extends Controller
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
     */
    public function index(Request $request)
    {
        $this->authorizeUser('productTag', 'view');
        if ($request->ajax()) {
            $data = ProductTag::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function($row){
                  return $row->mainProductCategories;

                })
                ->make(true);
        }

        return view('admin.dashboard.productTags.index',);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeUser('productTag', 'create');
        return view('admin.dashboard.productTags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeUser('productTag', 'create');

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_tags', 'name'),
            ],
        ], [
            'name.required' => 'The productTags Name field is required.',
            'name.string' => 'The productTags Name field must be a string.',
            'name.max' => 'The productTags Name may not be greater than :max characters.',
            'name.unique' => 'The productTags Name has already been taken.',
            ]);

        try {
            $productTag = new productTag();
            $productTag->name = strtoupper($validatedData['name']);
            $productTag->save();

            return redirect()->route('admin.productTags.index')->with('success', 'productTag ' . $productTag->name . ' created successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.productTags.index')->with('error', 'productTag creation failed. ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizeUser('productTag', 'create');
        $productTag=ProductTag::findOrFail($id);
        return view('admin.dashboard.productTags.edit',compact('productTag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeUser('productTag', 'edit');
        $productTag=ProductTag::findOrFail($id);
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_tags', 'name')->ignore($id),
            ],
        ], [
            'name.required' => 'The productTags Name field is required.',
            'name.string' => 'The productTags Name field must be a string.',
            'name.max' => 'The productTags Name may not be greater than :max characters.',
            'name.unique' => 'The productTags Name has already been taken.',
            ]);

        try {
            $productTag->name = strtoupper($validatedData['name']);
            $productTag->save();

            return redirect()->route('admin.productTags.index')->with('success', 'product Tag ' . $productTag->name . ' Updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.productTags.index')->with('error', 'product Tag Updated failed. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeUser('productTag', 'delete');
        try {
            $productTag = ProductTag::findOrFail($id);
            $productTag->delete();
            return redirect()->route('admin.productTags.index')->with('success', 'product Tag ' . $productTag->name . ' deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
}

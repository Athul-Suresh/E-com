<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCondition;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use DataTables;
class ProductConditionController extends Controller
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
        $this->authorizeUser('productCondition', 'view');
        if ($request->ajax()) {
            $data = ProductCondition::latest()->get();
            return Datatables::of($data)
                ->make(true);
        }

        return view('admin.dashboard.productConditions.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeUser('productCondition', 'create');
        return view('admin.dashboard.productConditions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeUser('productCondition', 'create');
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_conditions', 'name')
            ],
        ], [
            'name.required' => 'The Product Condition Name field is required.',
            'name.string' => 'The Product Condition Name field must be a string.',
            'name.max' => 'The Product Condition Name may not be greater than :max characters.',
            'name.unique' => 'The Product Condition Name has already been taken.',
            ]);

        try {
            $productCondition=new ProductCondition();
            $productCondition->name = strtoupper($validatedData['name']);
            $productCondition->status = 1;
            $productCondition->save();

            return redirect()->route('admin.productConditions.index')->with('success', 'product Condition ' . $productCondition->name . ' Created successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.productConditions.index')->with('error', 'product Condition Creation failed. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizeUser('productCondition', 'edit');
        $productCondition = ProductCondition::findOrFail($id);
        return view('admin.dashboard.productConditions.edit', compact('productCondition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeUser('productCondition', 'edit');
        $productCondition=ProductCondition::findOrFail($id);
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_conditions', 'name')->ignore($id),
            ],
        ], [
            'name.required' => 'The productConditions Name field is required.',
            'name.string' => 'The productConditions Name field must be a string.',
            'name.max' => 'The productConditions Name may not be greater than :max characters.',
            'name.unique' => 'The productConditions Name has already been taken.',
            ]);

        try {
            $productCondition->name = strtoupper($validatedData['name']);
            $productCondition->status = 1;
            $productCondition->save();

            return redirect()->route('admin.productConditions.index')->with('success', 'product Condition ' . $productCondition->name . ' Updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.productConditions.index')->with('error', 'product Condition Updation failed. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeUser('productCondition', 'delete');

        try {
            $productCondition = ProductCondition::findOrFail($id);
            $productCondition->delete();
            return redirect()->route('admin.productConditions.index')->with('success', 'Product Condition ' . $productCondition->name . ' deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $this->authorizeUser('productCondition', 'edit');
        try {
            $productCondition = ProductCondition::findOrFail($id);
            $productCondition->status == 1 ? $productCondition->status = 0 : $productCondition->status = 1;
            $productCondition->save();
            return response()->json(['success' => true, 'status' => $productCondition->status]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use DataTables;

class ProductBrandController extends Controller
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
        $this->authorizeUser('brand', 'view');
        $brands = Brand::all();

        if($request->ajax()){
            $data = Brand::latest()->get();
            return Datatables::of($data)
                ->make(true);
        }
        else {
            return view('admin.dashboard.brands.index', compact('brands'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeUser('brand', 'create');

        return view('admin.dashboard.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeUser('brand', 'create');

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name'),
            ],
            'logo' => 'nullable|image|max:2048',
            'featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
        ], [
            'name.required' => 'The brand name field is required.',
            'name.string' => 'The brand name field must be a string.',
            'name.max' => 'The brand name may not be greater than :max characters.',
            'name.unique' => 'The brand name has already been taken.',
            'logo.image' => 'The logo must be an image file.',
            'logo.max' => 'The logo may not be greater than :max kilobytes.',
            'featured.boolean' => 'The featured field must be a boolean value.',
            'status.boolean' => 'The status field must be a boolean value.',
            'meta_title.max' => 'The meta title may not be greater than :max characters.',
            'meta_description.max' => 'The meta description may not be greater than :max characters.',
            'meta_keyword.max' => 'The meta keyword may not be greater than :max characters.',
        ]);

        try {
            $brand = new Brand();
            $brand->name = strtoupper($validatedData['name']);
            if ($request->hasFile('logo')) {
                    $image = $request->file('logo');
                    $extension = $image->getClientOriginalExtension();
                    $fileName = Str::uuid() . '.' . $extension;
                    $image->storeAs('public/uploads/brands', $fileName);
                    $brand->logo =$fileName;
            }
            $brand->featured = $validatedData['featured'] ?? 0;
            $brand->status = 1;
            $brand->meta_title = $validatedData['meta_title'];
            $brand->meta_description = $validatedData['meta_description'];
            $brand->meta_keyword = $validatedData['meta_keyword'];
            $brand->save();

            return redirect()->route('admin.brands.index')->with('success', 'Brand ' . $brand->name . ' created successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.brands.index')->with('error', 'Brand creation failed. ' . $e->getMessage());
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
    public function edit($id)
    {
        $this->authorizeUser('brand', 'edit');

        $brand = Brand::findOrfail($id);
        return view('admin.dashboard.brands.edit', compact('brand'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeUser('brand', 'edit');

        try {
            $brand = Brand::findOrFail($id);

            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('brands')->ignore($id),
                ],
                'logo' => 'nullable|image|max:2048',
                'featured' => 'nullable|boolean',
                'status' => 'nullable|boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:255',
                'meta_keyword' => 'nullable|string|max:255',
            ], [
                'name.required' => 'The brand name field is required.',
                'name.string' => 'The brand name field must be a string.',
                'name.max' => 'The brand name may not be greater than :max characters.',
                'name.unique' => 'The brand name has already been taken.',

                'featured.boolean' => 'The featured field must be a boolean value.',
                'status.boolean' => 'The status field must be a boolean value.',
                'meta_title.max' => 'The meta title may not be greater than :max characters.',
                'meta_description.max' => 'The meta description may not be greater than :max characters.',
                'meta_keyword.max' => 'The meta keyword may not be greater than :max characters.',
            ]);

            $brand->name = strtoupper($request->input('name'));
            $brand->featured = $request->input('featured', 0);
            $brand->status = $request->input('status', 1);
            $brand->meta_title = $request->input('meta_title');
            $brand->meta_description = $request->input('meta_description');
            $brand->meta_keyword = $request->input('meta_keyword');

            // $brand->logo =  $request->input('logo') ? $brand->logo : null; //temporary
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $extension = $image->getClientOriginalExtension();
                $fileName = Str::uuid() . '.' . $extension;
                $image->storeAs('public/uploads/brands', $fileName);
                $brand->logo =$fileName;
            }
            if ($brand->isDirty('name')) {
                $brand->slug = $brand->generateSlug($request->input('name'));
            }
            $brand->save();
            return redirect()->route('admin.brands.index')->with('success', 'Brand ' . $brand->name . ' Updated successfully.');
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
        $this->authorizeUser('brand', 'delete');

        try {
            $brand = Brand::findOrFail($id);
            $imagePath = 'public/uploads/brands/' . $brand->logo;
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
            $brand->delete();
            return redirect()->route('admin.brands.index')->with('success', 'Brand ' . $brand->name . ' deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $this->authorizeUser('brand', 'edit');
        try {
            $brand = Brand::findOrFail($id);
            $brand->status == 1 ? $brand->status = 0 : $brand->status = 1;
            $brand->save();
            return response()->json(['success' => true, 'status' => $brand->status]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

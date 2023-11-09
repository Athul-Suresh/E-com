<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\MainProductCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCondition;
use App\Models\ProductGallery;
use App\Models\ProductTag;
use App\Models\Unit;
use App\Models\Voucher;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use DataTables;

class ProductController extends Controller
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
        $this->authorizeUser('product', 'view');
        if ($request->ajax()) {
            $data = Product::with("mainProductCategories")->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function($row){
                  return $row->mainProductCategories;
                
                })
                ->make(true);
        }

        return view('admin.dashboard.product.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeUser('product', 'create');
        $categories = MainProductCategory::get();
        $brands = Brand::where('status', 1)->get();
        $tags = ProductTag::get();
        $condition = ProductCondition::get();
        $units = Unit::get();
        $vouchers = Voucher::where('status', 1)->get();
        return view('admin.dashboard.product.create', ['vouchers'=>$vouchers,'units' => $units, 'conditions' => $condition, 'categories' => $categories, 'brands' => $brands, 'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:main_product_categories,id',

            'brand' => 'required|exists:brands,id',
            'unit' => 'required|exists:units,id',
            'condition' => 'required|exists:product_conditions,id',
            'voucher' => 'nullable|exists:vouchers,id',
            'discount_amount' => 'nullable|numeric',
            'discount_amount_type' => 'nullable|integer|in:1,2',
            'summary' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=550,max_height=550',
            'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=550,max_height=550',

            'is_featured' => 'nullable|integer',
            'is_refundable' => 'nullable|integer',
            'has_warranty' => 'nullable|integer',
            'is_cod' => 'nullable|integer',

            'stock' => 'required|integer',

            'purchase_price' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'offer_price' => 'nullable|numeric',

            'min_purchase_qty' => 'required|numeric',
            'max_purchase_qty' => 'required|numeric',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',

        ],
        [
            'thumbnail.dimensions' => 'The thumbnail field has invalid image dimensions.'
        ]
    );


        try {
            DB::beginTransaction();

            $product = new Product();
            $product->name = $validatedData['name'];
            $product->brand_id = $validatedData['brand'];
            $product->unit_id = $validatedData['unit'];
            $product->condition_id = $validatedData['condition'];
            $product->voucher_id = $validatedData['voucher'];

            $product->purchase_price = $validatedData['purchase_price'];
            $product->unit_price = $validatedData['unit_price'] ;
            $product->offer_price = $validatedData['offer_price'] ?? 0;
            $product->stock = $validatedData['stock'];
            $product->discount_type = $validatedData['discount_amount_type'];
            $product->discount = $validatedData['discount_amount'] ?? 0;
            $product->short_description = $validatedData['summary'];
            $product->long_description = $validatedData['description'];

            $product->thumbnail = null;

            // Handle the user's profile image
            if ($request->hasFile('thumbnail')) {
                // $image = $request->file('image');
                $image = $request->file('thumbnail');
                    $extension = $image->getClientOriginalExtension();
                    $fileName = Str::uuid() . '.' . $extension;
                 $product->thumbnail = $fileName;
                 $imagePath = $image->storeAs('public/uploads/product/thumbnail', $fileName, 'public');
                 Storage::disk('public')->setVisibility($imagePath, 'public');

            }


            $product->featured = $validatedData['is_featured']  ?? 0;
            $product->status = 1;
            $product->refundable = $validatedData['is_refundable']  ?? 0;
            $product->cod = $validatedData['is_cod']  ?? 0;
            $product->warranty = $validatedData['has_warranty']  ?? 0;

            $product->min_qty = $validatedData['min_purchase_qty']  ?? 0;
            $product->max_qty = $validatedData['max_purchase_qty']  ?? 0;

            $product->meta_title = $validatedData['meta_title'];
            $product->meta_description = $validatedData['meta_description'];
            $product->meta_keyword = $validatedData['meta_keyword'];
            $product->save();

            $categoryIds =  $validatedData['categories']; // Array of  MainProductCategory IDs
            $product->mainProductCategories()->sync($categoryIds);

            // Save product images
            //   if ($request->hasFile('gallery')) {
            //     $images = $request->file('gallery');
            //     foreach ($images as $image) {
            //         $fileName = $image->getClientOriginalName();
            //         $path = $image->store('public/uploads/product/gallery');
            //         $productImage = new ProductGallery();
            //         $productImage->image_path = $fileName;
            //         $productImage->product_id = $product->id;
            //         $productImage->save();
            //     }
            // }
            if ($request->hasFile('gallery')) {
                $images = $request->file('gallery');
                foreach ($images as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $fileName = Str::uuid() . '.' . $extension;
                    // $imagePath=$image->storeAs('public/uploads/product/gallery', $fileName);
                    $imagePath=$image->storeAs('public/uploads/product/gallery', $fileName);
                    Storage::disk('public')->setVisibility($imagePath, 'public');
                    $productImage = new ProductGallery();
                    $productImage->image_path = $fileName;
                    $productImage->product_id = $product->id;
                    $productImage->save();
                }
            }





            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollback();
            return redirect()->route('admin.products.create')->with('error', 'Failed to create product. Try again Later');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect('/admin/products');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizeUser('product', 'edit');
        $product = Product::with('gallery')->findOrFail($id);
        // dd($product);
        $categories = MainProductCategory::get();
        $brands = Brand::where('status', 1)->get();
        $tags = ProductTag::get();
        $condition = ProductCondition::get();
        $units = Unit::get();
        $vouchers = Voucher::where('status', 1)->get();

        $gallery = ProductGallery::where(['product_id' => $product->id])->get();
        return view(
            'admin.dashboard.product.edit',
            [
                'units' => $units,
                'conditions' => $condition,
                'categories' => $categories,
                'brands' => $brands,
                'tags' => $tags,
                'product' => $product,
                'vouchers' => $vouchers,
            ]
        );
    }

    /**
             * Update the specified resource in storage.
             */
            // public function update(Request $request, string $id)
            // {
            //     $this->authorizeUser('product', 'edit');

            //     $product = Product::findOrFail($id);


            //     $validatedData = $request->validate([
            //         'name' => 'required|string|max:255',
            //         'categories' => 'required|array|min:1',
            //         'categories.*' => 'exists:main_product_categories,id',

            //         'brand' => 'required|exists:brands,id',
            //         'unit' => 'required|exists:units,id',
            //         'condition' => 'required|exists:product_conditions,id',
            //         'discount_amount' => 'nullable|numeric',
            //         'discount_amount_type' => 'nullable|integer|in:1,2',
            //         'summary' => 'required|string',
            //         'description' => 'required|string',
            //         'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //         'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            //         'is_featured' => 'nullable|integer',
            //         'is_refundable' => 'nullable|integer',
            //         'has_warranty' => 'nullable|integer',
            //         'is_cod' => 'nullable|integer',

            //         'stock' => 'required|integer',

            //         'purchase_price' => 'required|numeric',
            //         'unit_price' => 'required|numeric',
            //         'offer_price' => 'nullable|numeric',

            //         'min_purchase_qty' => 'required|numeric',
            //         'max_purchase_qty' => 'required|numeric',

            //         'meta_title' => 'nullable|string|max:255',
            //         'meta_description' => 'nullable|string|max:255',
            //         'meta_keyword' => 'nullable|string|max:255',

            //     ]);

            //     try {
            //         DB::beginTransaction();

            //          $product->name = $validatedData['name'];
            //          $product->brand_id = $validatedData['brand'];
            //          $product->unit_id = $validatedData['unit'];
            //          $product->condition_id = $validatedData['condition'];
            //          $product->purchase_price = $validatedData['purchase_price'];
            //          $product->unit_price = $validatedData['unit_price'];
            //          $product->offer_price = $validatedData['offer_price']??0;
            //          $product->stock = $validatedData['stock'];
            //          $product->discount_type = $validatedData['discount_amount_type'];
            //          $product->discount = $validatedData['discount_amount']??0;
            //          $product->short_description = $validatedData['summary'];
            //          $product->long_description = $validatedData['description'];

            //         //  $product->thumbnail = null;

            //         //  if ($request->hasFile('thumbnail')) {
            //         //      $image = $request->file('thumbnail');
            //         //      $fileName = $image->getClientOriginalName();
            //         //      $product->thumbnail = $fileName;
            //         //      $image->store('public/uploads/product/thumbnail');
            //         //  }
            //          if ($request->hasFile('thumbnail')) {
            //             $image = $request->file('thumbnail');
            //             $extension = $image->getClientOriginalExtension();
            //             $fileName = Str::uuid() . '.' . $extension;
            //             $image->storeAs('public/uploads/product/thumbnail', $fileName);
            //             $product->thumbnail =$fileName;
            //             }



            //          $product->featured = $validatedData['is_featured'];
            //          $product->status = 1;
            //          $product->refundable = $validatedData['is_refundable'];
            //          $product->cod = $validatedData['is_cod'];
            //          $product->warranty = $validatedData['has_warranty'];

            //          $product->min_qty = $validatedData['min_purchase_qty'];
            //          $product->max_qty = $validatedData['max_purchase_qty'];

            //          $product->meta_title = $validatedData['meta_title'];
            //          $product->meta_description = $validatedData['meta_description'];
            //          $product->meta_keyword = $validatedData['meta_keyword'];
            //          $product->save();

            //          $categoryIds =  $validatedData['categories']; // Array of  MainProductCategory IDs
            //          $product->mainProductCategories()->sync($categoryIds);

            //           // Update product images
            //         //   if ($request->hasFile('gallery')) {
            //         //     $images = $request->file('gallery');
            //         //     foreach ($images as $image) {
            //         //         $fileName = $image->getClientOriginalName();
            //         //         $path = $image->store('public/uploads/product/gallery');
            //         //         $productImage = new ProductGallery();
            //         //         $productImage->image_path = $fileName;
            //         //         $productImage->product_id = $product->id;
            //         //         $productImage->save();
            //         //     }
            //         // }
            //         if ($request->hasFile('gallery')) {
            //             $images = $request->file('gallery');
            //             foreach ($images as $image) {
            //                 $extension = $image->getClientOriginalExtension();
            //                 $fileName = Str::uuid() . '.' . $extension;
            //                 $image->storeAs('public/uploads/product/gallery', $fileName);
            //                 $productImage = new ProductGallery();
            //                 $productImage->image_path =$fileName;
            //                 $productImage->product_id = $product->id;
            //                 $productImage->save();
            //             }
            //             $product->gallery()->delete();
            //         }



            //          DB::commit();
            //          return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
            //     } catch (\Exception $e) {
            //        // dd($e->getMessage());
            //         DB::rollback();
            //         return redirect()->route('admin.products.create')->with('error', 'Failed to create product. Try again Later');

            //     }




    // }

    public function update(Request $request, string $id)
    {
        $this->authorizeUser('product', 'edit');

        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:main_product_categories,id',
            'brand' => 'required|exists:brands,id',
            'unit' => 'required|exists:units,id',
            'condition' => 'required|exists:product_conditions,id',
            'voucher' => 'nullable|exists:vouchers,id',
            'discount_amount' => 'nullable|numeric',
            'discount_amount_type' => 'nullable|integer|in:1,2',
            'summary' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_refundable' => 'nullable|boolean',
            'has_warranty' => 'nullable|boolean',
            'is_cod' => 'nullable|boolean',
            'stock' => 'required|integer',
            'purchase_price' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'min_purchase_qty' => 'required',
            'max_purchase_qty' => 'required',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
        ]);

        try {


        DB::beginTransaction();

        $product->name = $validatedData['name'];
        $product->brand_id = $validatedData['brand'];
        $product->unit_id = $validatedData['unit'];
        $product->condition_id = $validatedData['condition'];
        $product->voucher_id = $validatedData['voucher'];

        $product->purchase_price = $validatedData['purchase_price'];
        $product->unit_price = $validatedData['unit_price'] ;
        $product->offer_price = $validatedData['offer_price'] ?? 0;
        $product->stock = $validatedData['stock'];
        $product->discount_type = $validatedData['discount_amount_type'];
        $product->discount = $validatedData['discount_amount'] ?? 0;
        $product->short_description = $validatedData['summary'];
        $product->long_description = $validatedData['description'];



        // Handle product thumbnail
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $extension = $image->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            $image->storeAs('public/uploads/product/thumbnail', $fileName);
            $product->thumbnail = $fileName;
        }

        // Handle product gallery
        if ($request->hasFile('gallery')) {
            $images = $request->file('gallery');
            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $fileName = Str::uuid() . '.' . $extension;
                $image->storeAs('public/uploads/product/gallery', $fileName);
                $productImages[] = ['image_path' => $fileName];
            }
            $product->gallery()->delete();
            $product->gallery()->createMany($productImages);
        }
        $product->save();
        $categoryIds =  $validatedData['categories']; // Array of  MainProductCategory IDs
        $product->mainProductCategories()->sync($categoryIds);


        DB::commit();
        return redirect()->route('admin.products.index')->with('success', 'Product update successfully.');
    } catch (\Exception $e) {
        // dd($e->getMessage());
        DB::rollback();
        return redirect()->route('admin.products.create')->with('error', 'Failed to update product. Try again Later');
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeUser('product', 'delete');
        try {
            $product = Product::findOrFail($id);
            $product->mainProductCategories()->detach();
            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $this->authorizeUser('product', 'edit');
        try {
            $product = Product::findOrFail($id);
            $product->status == 1 ? $product->status = 0 : $product->status = 1;
            $product->save();
            return response()->json(['success' => true, 'status' => $product->status]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function updateFeature(Request $request, $id)
    {
        $this->authorizeUser('product', 'edit');
        try {
            $product = Product::findOrFail($id);
            $product->status == 1 ? $product->featured = 0 : $product->featured = 1;
            $product->save();
            return response()->json(['success' => true, 'status' => $product->featured]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

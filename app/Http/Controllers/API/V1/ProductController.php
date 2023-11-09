<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCart as ResourcesProductCart;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductWishListResource;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductWishList;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isFalse;

class ProductController extends Controller
{

    public function products(Request $request)
    {
        try {

            $brands = $request->input('brand', []);

            $categories = $request->input('category');
            $search = $request->input('q');

            $page = max(1, $request->input('page', 1)); // Adjusted to avoid page value less than 1
            $perPage = 10; // Number of products per page
            $products = Product::where('status', 1);


            if (!empty($search)) {
                $products->where('name', 'LIKE', '%' . $search . '%');
            }

            if (!empty($brands)) {
                $products->whereHas('brand', function ($query) use ($brands) {
                    $query->whereIn('slug', $brands);
                });
            }

            if (!empty($categories)) {
                $products->whereHas('mainProductCategories', function ($query) use ($categories) {
                    $query->whereIn('slug', $categories);
                });
            }



            $minPrice = $request->has('min') ? floatval($request->input('min')) : null;
            $maxPrice = $request->has('max') ? floatval($request->input('max')) : null;

            if ($minPrice !== null && $minPrice != 0) {
                $products->where('unit_price', '>=', $minPrice);
            }

            if ($maxPrice !== null && $maxPrice != 0) {
                $products->where('unit_price', '<=', $maxPrice)->where('unit_price', '>=', $maxPrice);
            }

            $sortOrder = $request->input("sort");


            if($sortOrder == "featured"){
               $products->where("featured" , 1);
            }
            else {
                if(isset($sortOrder)){
                    $products->orderBy("created_at" , $sortOrder);
                }
                else {
                    $products->orderBy("created_at" , "asc");
                }
            }

            $products = $products->paginate($perPage, ['*'], 'page', $page);


            $resource = ProductResource::collection($products);

            // $resource->additional([
            //     'meta' => [
            //         'pagination' => [
            //             'total' => $products->total(),
            //             'count' => $products->count(),
            //             'per_page' => $products->perPage(),
            //             'current_page' => $products->currentPage(),
            //             'total_pages' => $products->lastPage(),
            //         ]
            //     ]
            // ]);



            $pagination = [
                'total' => $products->total(),
                'count' => $products->count(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'total_pages' => $products->lastPage(),
            ];
            return response()->json(['data' => $resource, 'pagination' => $pagination], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Product not found', 'status' => 404]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Internal server error', 'e' => $exception->getMessage()], 500);
        }
    }

    public function productSingle(string $id): JsonResponse
    {
        try {
            $product = Product::where(["slug" => $id, 'status' => 1])->firstOrFail();
            return response()->json(new ProductResource($product));
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Product not found', 'status' => 404]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function productSimilar(string $category): JsonResponse
    {
        try {
            $products = Product::whereHas('mainProductCategories', function ($query) use ($category) {
                $query->where('slug', $category);
            })->where('status', 1)->get();
            if ($products->isEmpty()) {
                return response()->json(['message' => 'No Related products'], 404);
            }
            return response()->json(ProductResource::collection($products));
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Product not found', 'status' => 404]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function productFeatured(): JsonResponse
    {
        try {
            $products = Product::where(["featured" => 1, 'status' => 1])->get();
            return response()->json(ProductResource::collection($products));
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Product not found', 'status' => 404]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }



    public function productCartOffline(Request $request): JsonResponse
    {
        try {
            $items = $request->get('items');
            if (empty($items)) {
                return response()->json(['status' => false, 'message' => 'Empty Cart']);
            } else {
                $productCart= Product::whereIn('id', $items)->get();
                if ($productCart->isEmpty()) {
                    return response()->json(['status' => false, 'message' => 'Your cart is empty!']);
                } else {
                    return response()->json(['status' => true, "data" => ProductResource::collection($productCart)]);
                }
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => false, 'message' => 'Your cart is empty!']);
        } catch (\Exception $exception) {
            // return response()->json(['status'=> false,'message' =>  $exception->getMessage()], 500);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }
    public function productCart(): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            if (is_null($user)) {
                return response()->json(['status' => false, 'message' => 'login to view Cart ']);
            } else {
                $productCart = ProductCart::where(["user_id" => $user->id])->with('product')->get();
                if ($productCart->isEmpty()) {
                    return response()->json(['status' => false, 'message' => 'Your cart is empty!']);
                } else {
                    return response()->json(['status' => true, "data" => ResourcesProductCart::collection($productCart)]);
                }
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => false, 'message' => 'Your cart is empty!']);
        } catch (\Exception $exception) {
            // return response()->json(['status'=> false,'message' =>  $exception->getMessage()], 500);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function productCartAdd(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'product' => 'required',
                'quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Invalid input data'], 400);
            }

            $user = Auth::guard('api')->user();

            if (is_null($user)) {
                return response()->json(['status' => false, 'message' => 'Login to Add Cart']);
            }


            $productCart = ProductCart::where(['user_id' => $user->id, 'product_id' => $request->product])->first();


            if (is_null($productCart)) {
                $productCart = new ProductCart();
                $productCart->user_id = $user->id;
                $productCart->product_id = $request->product;
                $productCart->quantity = $request->quantity;
                $productCart->save();
                return response()->json(['status' => true, 'message' => 'Product added to cart successfully', 'data' => new ResourcesProductCart($productCart)]);
            } else {
                return response()->json(['status' => false, 'message' => 'Product already added to cart']);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $exception->getMessage()], 500);
        }
    }

    // public function productCartOfflineAdd(Request $request): JsonResponse
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'product' => 'required',
    //             'quantity' => 'required',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['message' => 'Invalid input data'], 400);
    //         }

    //         $user = Auth::guard('api')->user();

    //         if (is_null($user)) {
    //             return response()->json(['status' => false, 'message' => 'Login to Add Cart']);
    //         }

    //         $products = $request->input('product', []);
    //         $quantities = $request->input('quantity', []);

    //         if (count($products) !== count($quantities)) {
    //             return response()->json(['status' => false, 'message' => 'Invalid request']);
    //         }

    //         $response = [];

    //         foreach ($products as $index => $productSlug) {
    //             $product = Product::where('slug', $productSlug)->first();

    //             if (is_null($product)) {
    //                 $response[] = ['status' => false, 'message' => 'There is no product with the specified slug'];
    //                 continue;
    //             }

    //             $productCart = ProductCart::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

    //             if (is_null($productCart)) {
    //                 $productCart = new ProductCart();
    //                 $productCart->user_id = $user->id;
    //                 $productCart->product_id = $product->id;
    //                 $productCart->quantity = $quantities[$index];
    //                 $productCart->save();
    //                 $response[] = ['status' => true, 'message' => 'Product added to cart successfully', 'data' => new ResourcesProductCart($productCart)];
    //             } else {
    //                 $response[] = ['status' => false, 'message' => 'Product already added to cart'];
    //             }
    //         }
    //     } catch (\Exception $exception) {
    //         return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $exception->getMessage()], 500);
    //     }
    // }

    public function productCartUpdate(Request $request): JsonResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                //  'user' => 'required|exists:users,id',
                'product' => 'required',
                'quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => true, 'message' => 'Invalid input data']);
            }
            try {
                $user = Auth::guard('api')->user();
                $productCart = Product::where(['user' => $validator->input('user'), 'product_id' => $validator->input('product')])->firstOrFail();
                $productCart->quantity = $validator->input('quantity');
                $productCart->user_id = $user->id; //$validator->input('user');
                $productCart->product_id = $validator->input('product');
                $productCart->save();
                return response()->json(['status' => true, 'message' => 'Product Cart Updated']);
            } catch (ModelNotFoundException $e) {
                return response()->json(['status' => false, 'message' => 'Product cart not found']);
            }
            return response()->json(['status' => true, 'message' => 'Product cart updated successfully', 'data' => $productCart]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $exception->getMessage()], 500);
        }
    }

    public function productCartQuantityUpdate(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'product' => 'required',
                'quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $user = Auth::guard('api')->user();
            $productCart = ProductCart::where([
                'user_id' => $user->id,
                'product_id' => $request->input('product')
            ])->firstOrFail();

            $product = Product::findOrFail($productCart->product_id);
            $stock = $product->stock ?: 0;

            if ($stock < $request->quantity) {
                return response()->json(['status' => false, 'message' => 'Out of Stock']);
            } else {
                $productCart->quantity = $request->quantity;
                $productCart->save();
                return response()->json(['status' => true, 'message' => 'Product Cart Updated']);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Login to Update Cart', 'error' => $e->getMessage()]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $exception->getMessage()], 500);
        }
    }

    public function productCartRemove(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'product' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'Invalid input data'], 400);
            }
            $user = Auth::guard('api')->user();
            if (is_null($user)) {
                return response()->json(['status' => false, 'message' => 'Login to Remove Products from Cart']);
            }
            $productCart = ProductCart::where(['user_id' => $user->id, 'product_id' => $request->product])->first();
            if (is_null($productCart)) {
                return response()->json(['status' => false, 'message' => 'Product not found in cart']);
            }
            $productCart->delete();
            return response()->json(['status' => true, 'message' => 'Product removed from cart successfully']);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $exception->getMessage()], 500);
        }
    }

    // Product Wish List

    public function productaddToWishlist(): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            if (is_null($user)) {
                return response()->json(['status' => false, 'message' => 'login to view Cart ']);
            } else {
                $productWishList = ProductWishList::where(["user_id" => $user->id])->with('product')->get();
                if ($productWishList->isEmpty()) {
                    return response()->json(['status' => false, 'message' => 'Your Wish list empty!']);
                }
            }
            return response()->json(['status' => true, "data" => ProductWishListResource::collection($productWishList)]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['status' => false, 'message' => 'Your Wish list is empty!']);
        } catch (\Exception $exception) {
            // return response()->json(['status'=> false,'message' =>  $exception->getMessage()], 500);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function productaddToWishlistAdd(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['product' => 'required',]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Invalid input data'], 400);
            }

            $productId = $request->input('product');
            $userId = Auth::guard('api')->user()->id;
            $exist = ProductWishList::where(['user_id' => $userId, 'product_id' => $productId])->first();
            if (is_null($exist)) {
                $wishlist = ProductWishList::firstOrCreate([
                    'user_id' => $userId,
                    'product_id' => $productId,
                ]);
                return response()->json(['status' => true, 'message' => 'Product Added to Wishlist successfully', 'data' => new ProductWishListResource($wishlist)]);
            } else {
                return response()->json(['status' => false, 'message' => 'Product Already in Wishlist']);
            }
        } catch (\Throwable $th) {
            // return response()->json(['status' =>false,'message' =>$th->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $th->getMessage()], 500);
        }
    }
    public function productaddToWishlistRemove(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Invalid input data'], 400);
            }

            $productId = $request->input('product');
            $userId = Auth::guard('api')->user()->id;

            $wishlist = ProductWishList::where(['user_id' => $userId, 'product_id' => $productId])->first();

            if (!is_null($wishlist)) {
                $wishlist->delete();
                return response()->json(['status' => true, 'message' => 'Product removed from Wishlist']);
            } else {
                return response()->json(['status' => false, 'message' => 'Product not found in Wishlist']);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $exception->getMessage()], 500);
        }
    }

    public function productRecomment(): JsonResponse
    {
        try {
            $products = Product::inRandomOrder()->limit(10)->get();

            return response()->json(['staus' => true, 'data' => ProductResource::collection($products)]);
            if ($products->empty()) {
                return response()->json(['status' => false, 'message' => 'Products not found']);
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Products not found', 'status' => 404]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => "Internal server error"], 500);
        }
    }
}

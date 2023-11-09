<?php

use App\Http\Controllers\API\V1\ContactController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/upload', [UploadController::class, 'apiUpload'])->name('api.upload');
// Route::get('/images/{imageName}', [UploadController::class, 'getImage'])->name('api.image');


use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\MediaAPIController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\ProductBrandController;
use App\Http\Controllers\API\V1\ProductCategoryController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\ReviewController;

Route::prefix('v1/')->group(function () {

Route::post('signup', [UserController::class, 'userSignUp']);
Route::post('signin', [UserController::class, 'userSignIn']);
Route::get('states', [UserController::class, 'userStates']);
Route::post('signin/social',[UserController::class,'userSocial']);
Route::post('auth/resetlink',[UserController::class,'userForgot']); // Reset link to Reset Password
Route::post('auth/token/verify',[UserController::class,'userResetLinkVerify']); // Verify Reset Link
Route::post('auth/reset',[UserController::class,'userResetPassword']); // Reset Password

    // Route::apiResource('brands',ProductBrandController::class); // Product Brands
    Route::get('brands',[ProductBrandController::class,'brands']); // Product Brands
    Route::get('categories',[ProductCategoryController::class,'categories']); // Product Categories
    Route::get('category/{slug}',[ProductCategoryController::class,'category']); // Product Category


    Route::get('media/brand/{slug}',[MediaAPIController::class,'brand']); // Brand Logo
    Route::get('media/product/{slug}',[MediaAPIController::class,'product']); // Product Thumbnail
    Route::get('media/products/{slug}',[MediaAPIController::class,'products']); // Product Gallery


    Route::get('products',[ProductController::class,'products']); // Get all products
    Route::get('product/{slug}',[ProductController::class,'productSingle']); // Product single
    Route::get('product/recomment/{slug}',[ProductController::class,'productSimilar']); // Related product based on Category
    Route::get('products/featured',[ProductController::class,'productFeatured']); // Product is featured
    Route::get('products/recomment',[ProductController::class,'productRecomment']); // Product is recomment

    Route::get('product/cart/offline',[ProductController::class,'productCartOffline']); // Product Cart Offline
    Route::get('product/cart/items',[ProductController::class,'productCart'])->middleware('api.token'); // Product Cart
    Route::post('product/cart/add',[ProductController::class,'productCartAdd'])->middleware('api.token'); // Product Add to Cart
    // Route::post('product/cart/offlineadd',[ProductController::class,'productCartOfflineAdd'])->middleware('api.token'); // Product Add to Cart
    Route::post('product/cart/qty',[ProductController::class,'productCartQuantityUpdate'])->middleware('api.token'); // Product Qty Update
    Route::post('product/cart/remove',[ProductController::class,'productCartRemove'])->middleware('api.token'); // Product Delete from Cart

    Route::get('product/wishlist/items',[ProductController::class,'productaddToWishlist'])->middleware('api.token'); // Product Wishlist
    Route::post('product/wishlist/add',[ProductController::class,'productaddToWishlistAdd'])->middleware('api.token'); // Product Add to Wishlist
    Route::post('product/wishlist/remove',[ProductController::class,'productaddToWishlistRemove'])->middleware('api.token'); // Product Delete from Wishlist


    Route::get('user/{product}/reviews',[ReviewController::class,'reviews']);  // Product Reviews
    Route::get('user/{product}/review',[ReviewController::class,'userReviews'])->middleware('api.token');;  // Product User Review All
    Route::post('user/review',[ReviewController::class,'addReview'])->middleware('api.token');  // Product Review Add
    Route::post('user/review/update',[ReviewController::class,'updateReview'])->middleware('api.token');  // Product Review Update
    Route::post('user/review/remove',[ReviewController::class,'removeReview'])->middleware('api.token');  // Product Review Remove



    Route::get('user',[UserController::class,'userDetails'])->middleware('api.token'); // Get User Details
    Route::post('user/profile',[UserController::class,'userProfile'])->middleware('api.token'); // Update Profile
    Route::get('user/address',[UserController::class,'userAddress'])->middleware('api.token'); // Get User Details
    Route::post('user/address',[UserController::class,'userAddressAdd'])->middleware('api.token'); // Add User Address
    Route::get('user/{id}/address',[UserController::class,'userAddressSingle'])->middleware('api.token'); // Single User Address
    Route::post('user/address/{id}/update',[UserController::class,'userAddressUpdate'])->middleware('api.token'); // Update User Address
    Route::post('user/address/{id}/remove',[UserController::class,'userAddressRemove'])->middleware('api.token'); // Remove User Address


    Route::get('user/orders',[OrderController::class,'userOrders'])->middleware('api.token'); // Get User Orders
    Route::post('user/orders',[OrderController::class,'userOrderAdd'])->middleware('api.token'); // User Place Order
    Route::post('user/{order}/orders',[OrderController::class,'userOrderCancel'])->middleware('api.token'); // User Order Cancel

    // Route::post('user/order/{id}/cancel',[OrderController::class,'userOrderCancel'])->middleware('api.token'); // User Order Cancel

    Route::post('user/enquiry',[ContactController::class,'userEnquiry']); // User Enquiry



});
Route::fallback(function () {
    return response()->json(['message' => 'Api not found'], 404);
});

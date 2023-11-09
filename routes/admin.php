<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "Admin Web" middleware group. Make something great!
|
*/

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\AuthController;

use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductBrandController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductConditionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductMainCategoryController;
use App\Http\Controllers\Admin\ProductReportController;
use App\Http\Controllers\Admin\ProductTagController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\OrderReportController;

// Admin Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

// Admin Dashboard Routes
Route::middleware('auth:admin')->group(function () {
    // Dashboard
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout.submit');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard'); Route::get('/orders/count', [AdminController::class , "getOrderCounts"])->name('admin.orders.count');
    Route::get('/payments/count', [AdminController::class , "getPaymentsCounts"])->name('admin.payments.count');
    Route::get('/orders/count', [AdminController::class, "getOrderCounts"])->name('admin.orders.count');
    Route::get('/stock-and-sales', [ProductReportController::class, 'getStockAndSales'])->name('stock-and-sales');
    // Other Admin Routes
    // Admin Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/profile', [AdminController::class, 'profileCreate'])->name('admin.profile.save');

    // User Management
    Route::resource('/roles', RolesController::class, ['names' => 'admin.roles']);
    Route::resource('/users', UsersController::class, ['names' => 'admin.users']);
    Route::resource('/admins', AdminUsersController::class, ['names' => 'admin.admins']);

    // Product Management

    Route::resource('/categories', ProductCategoryController::class, ['names' => 'admin.categories']);
    Route::resource('/main-categories', ProductMainCategoryController::class, ['names' => 'admin.maincategories']);

    Route::resource('/brands', ProductBrandController::class, ['names' => 'admin.brands']);
    Route::put('/brands/{id}/status', [ProductBrandController::class, 'updateStatus'],)->name('admin.brands.updateStatus');

    Route::resource('/units', UnitController::class, ['names' => 'admin.units']);
    Route::resource('/productTags', ProductTagController::class, ['names' => 'admin.productTags']);

    Route::resource('/productCondition', ProductConditionController::class, ['names' => 'admin.productConditions']);
    Route::put('/productCondition/{id}/status', [ProductConditionController::class, 'updateStatus'],)->name('admin.productConditions.updateStatus');

    Route::resource('/products', ProductController::class, ['names' => 'admin.products']);
    Route::put('/products/{id}/status', [ProductController::class, 'updateStatus'],)->name('admin.products.updateStatus');
    Route::put('/products/{id}/feature', [ProductController::class, 'updateFeature'],)->name('admin.products.updateFeature');

    Route::resource('/vouchers', VoucherController::class, ['names' => 'admin.vouchers']);
    Route::put('/vouchers/{id}/status', [VoucherController::class, 'updateStatus'],)->name('admin.vouchers.updateStatus');


    Route::resource('/customers', CustomerController::class, ['names' => 'admin.customers']);


    Route::resource('orders', OrderController::class, ['names' => 'admin.orders']);
    // Route::get('/orders/{id}', [OrderController::class, 'orderEdit'])->name('admin.orders.edit');
    // Route::put('/orders/{id}', [OrderController::class, 'orderUpdate'])->name('admin.orders.update');

    Route::get('/enquiry', [ContactController::class, 'enquiry'])->name('admin.enquiry.index');
    Route::delete('/enquiry/destroy/{id}', [ContactController::class, 'destroy'])->name('admin.enquiry.destroy');

    Route::get('/review', [ReviewController::class, 'index'])->name('admin.review.index');

    Route::get('/report/products', [ProductReportController::class, 'products'])->name('admin.report.product');
    Route::get('/report/sales', [ProductReportController::class, 'salesReport'])->name('admin.report.sales');
    Route::get('/report/orders', [OrderReportController::class, 'orders'])->name('admin.report.order');
    Route::get('/report/stock', [ProductReportController::class, 'stockReport'])->name('admin.report.stock');
});

Route::fallback(function () {
    abort(404, 'Route not found');
});

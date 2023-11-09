<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\OrderMaster;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class ProductReportController extends Controller
{

    public $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    // Product Report
    public function products(Request $request)
    {

        return view('admin.dashboard.reports.product.list');
    }

    public function salesReport(Request $request)
    {
        $data = OrderDetail::all();
        return view('admin.dashboard.reports.product.sales', compact('data'));
    }

    public function stockReport(Request $request)
    {
        $data = Product::all();
        if ($request->ajax()) {
            $data = Product::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('total_sales', function ($item) {
                    $totalProductQty = $item->orderDetails
                        ->where('status', 'completed')
                        ->sum('product_qty');
                    return $totalProductQty;
                })
                ->make(true);
        }

        return view('admin.dashboard.reports.product.stock', compact('data'));
    }

    public function getStockAndSales()
    {
        $totalStock = Product::sum('stock');
        $totalSales = OrderDetail::sum('product_qty');

        return response()->json([
            'totalStock' => $totalStock,
            'totalSales' => $totalSales,
        ]);
    }
}

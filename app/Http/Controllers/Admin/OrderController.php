<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItems;
use App\Models\OrderMaster;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use DataTables;

class OrderController extends Controller
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

    public function index(Request $request)
    {
        $this->authorizeUser('order', 'view');
        $orders = OrderMaster::with(['details','user'])->get();

        if($request->ajax()){
            $data = OrderMaster::latest()->with(['details','user'])->get();
            return Datatables::of($data)
                ->make(true);
        }
        return view('admin.dashboard.orders.index', compact('orders'));
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeUser('order', 'edit');
        $orders = OrderMaster::findOrfail($id);
        return view('admin.dashboard.orders.edit', compact('orders'));
    }


    // public function update(Request $request, $id)
        // {
        //     $this->authorizeUser('order', 'edit');

        //     $request->validate([
        //         'selected_items' => 'required|array',
        //         'selected_items.*' => 'exists:order_items,id',
        //         'delivery_status' => 'required|array',
        //         'delivery_status.*' => Rule::in(['pending', 'processing', 'completed', 'cancelled']),
        //     ], [
        //         'delivery_status.array' => 'Select Product',
        //         'selected_items.required' => 'Please check any Item',
        //     ]);
        //     $selectedItems = $request->input('selected_items', []);
        //     $deliveryStatuses = $request->input('delivery_status', []);


        //     foreach ($selectedItems as $key=> $selectedItem){
        //         // update order of items status according to selected items status
        //         $item = OrderItems::findOrFail($selectedItem);
        //         $item->delivery_status = $deliveryStatuses[$key];
        //         $item->save();

        //         // Update Main order status based on order items Count
        //         $orderItemsCount = count($selectedItems);
        //         $completedItemsCount = count(array_filter($deliveryStatuses, function ($status) {
        //             return $status === 'completed';
        //         }));

        //         $mainOrder = Order::findOrFail($id);
        //         if ($completedItemsCount === $orderItemsCount) {
        //             $mainOrder->status = 'completed';
        //         } elseif ($completedItemsCount > 0 && $completedItemsCount < $orderItemsCount) {
        //             $mainOrder->status = 'processing';
        //         } else {
        //             $mainOrder->status = 'pending';
        //         }
        //         $mainOrder->save();


        //         //  Update Stock



        //     }


    // }

    public function update(Request $request, $id)
    {

        dd($request->all());

        $selectedIds = $request->input('selectedOrderIds');
        $delivery = $request->input('delivery_statuses');
        foreach ($selectedIds as $selectedId){
            
        }
    }

}


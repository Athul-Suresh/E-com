<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\OrderMaster;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

    public function orders(Request $request)
    {
        $this->authorizeUser('order', 'view');
        $orders = Order::all();
        return view('admin.dashboard.orders.index', compact('orders'));
    }

    public function orderEdit(Request $request, $id)
    {
        $this->authorizeUser('order', 'edit');
        $order = Order::findOrfail($id);
        return view('admin.dashboard.orders.edit', compact('order'));
    }


    public function orderUpdate(Request $request, $id)
    {
        $this->authorizeUser('order', 'edit');

        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:order_items,id',
            'delivery_status' => 'required|array',
            'delivery_status.*' => Rule::in(['pending', 'processing', 'completed', 'cancelled']),
        ], [
            'delivery_status.array' => 'Select Product',
            'selected_items.required' => 'Please check any Item',
        ]);
        $selectedItems = $request->input('selected_items', []);
        $deliveryStatuses = $request->input('delivery_status', []);


        foreach ($selectedItems as $key=> $selectedItem){
            // update order of items status according to selected items status
            $item = OrderItems::findOrFail($selectedItem);
            $item->delivery_status = $deliveryStatuses[$key];
            $item->save();

            // Update Main order status based on order items Count
            $orderItemsCount = count($selectedItems);
            $completedItemsCount = count(array_filter($deliveryStatuses, function ($status) {
                return $status === 'completed';
            }));

            $mainOrder = Order::findOrFail($id);
            if ($completedItemsCount === $orderItemsCount) {
                $mainOrder->status = 'completed';
            } elseif ($completedItemsCount > 0 && $completedItemsCount < $orderItemsCount) {
                $mainOrder->status = 'processing';
            } else {
                $mainOrder->status = 'pending';
            }
            $mainOrder->save();


            //  Update Stock



        }


    }


    public function orderIndex(Request $request)
    {
        $this->authorizeUser('order', 'view');
        $orders = OrderMaster::with('details')->get();
        return view('admin.dashboard.orders.index', compact('orders'));
    }















































    // public function orderUpdate(Request $request, $id)
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

        //     try {
        //         DB::beginTransaction();

        //         // Retrieve the selected items and their delivery status
        //         $selectedItems = $request->input('selected_items', []);
        //         $deliveryStatuses = $request->input('delivery_status', []);

        //         // foreach ($selectedItems as $index => $itemId) {
        //             //     $item = OrderItems::findOrFail($itemId);
        //             //     $item->delivery_status = $deliveryStatuses[$index];
        //             //     $item->save();
        //             //     $mainOrder = Order::where('id', $item->order_id)->first();

        //             //     if ($item->delivery_status == "cancelled") {
        //             //         $mainOrder->total_amount = $mainOrder->total_amount - $item->price;
        //             //         $mainOrder->save();
        //             //     }

        //             //     //if ($item->delivery_status == 'completed') {
        //             //     // Remove item from cart
        //             //     // $cart = ProductCart::where(['user_id' => $item->order->user_id, 'product_id' => $item->product_id])->firstOrFail();
        //             //     // $cart->delete();

        //             //     // Subtract product stock
        //             //     // $product = Product::findOrFail($item->product_id);
        //             //     // $product->stock -= 1;
        //             //     // $product->save();
        //             //     //}
        //         // }


        //         // Order Item status update
        //         // Main Order  status update // if all Order Item status are completed then order status updated as completed
        //         // Main Order Total Amount Update if success
        //         // Stock Update if success




        //         DB::commit();
        //         return redirect()->route('admin.orders.index')->with('success', 'Order Updated successfully.');
        //     } catch (\Throwable $th) {
        //         DB::rollBack();
        //         return redirect()->route('admin.orders.index')->with('error', $th->getMessage() . ' Order updation error.');
        //     }
    // }



        // private function updateStock()
        // {

        // }

        // private function updateTotalAmount( $mainOrder,$item)
        // {
        //     $oldTotalAmount = $mainOrder->totalAmount;
        //     if ($item->delivery_status === 'cancelled') {
        //         $mainOrder->total_amount -= $item->price;
        //     } else {
        //         $mainOrder->total_amount = $oldTotalAmount;
        //     }
        //     if($mainOrder->save()){
        //         return true;
        //     }
        // }

// try {
            //     DB::beginTransaction();

            //     // Retrieve the selected items and their delivery status
            //     $selectedItems = $request->input('selected_items', []);
            //     $deliveryStatuses = $request->input('delivery_status', []);

            //     foreach ($selectedItems as $index => $itemId) {
            //         $item = OrderItems::findOrFail($itemId);
            //         $item->delivery_status = $deliveryStatuses[$index];
            //         $item->save();

            //         $mainOrder = Order::where('id', $item->order_id)->firstOrFail();
            //         if ($deliveryStatuses[$index] === 'cancelled') {
            //             $mainOrder = Order::where('id', $item->order_id)->firstOrFail();
            //             $mainOrder->total_amount -= $item->price;
            //             $mainOrder->save();
            //         } else {
            //             $mainOrder->total_amount += $item->price;
            //         }

            //         $product = Product::findOrFail($item->product_id);

            //         if ($deliveryStatuses[$index] === 'completed') {
            //             // Update stock for the product
            //             $product = Product::findOrFail($item->product_id);
            //             $product->stock -= $item->quantity;
            //             $product->save();
            //         }else{
            //             $product->stock += $item->quantity;
            //             $product->save();
            //         }

            //     }

            //     // Update order status based on order items
            //     $orderItemsCount = count($selectedItems);
            //     $completedItemsCount = count(array_filter($deliveryStatuses, function ($status) {
            //         return $status === 'completed';
            //     }));

            //     $mainOrder = Order::findOrFail($id);
            //     if ($completedItemsCount === $orderItemsCount) {
            //         $mainOrder->status = 'completed';
            //     } elseif ($completedItemsCount > 0 && $completedItemsCount < $orderItemsCount) {
            //         $mainOrder->status = 'processing';
            //     } else {
            //         $mainOrder->status = 'pending';
            //     }
            //     $mainOrder->save();

            //     DB::commit();
            //     return redirect()->route('admin.orders.index')->with('success', 'Order Updated successfully.');
            // } catch (\Throwable $th) {
            //     DB::rollBack();
            //     return redirect()->route('admin.orders.index')->with('error', $th->getMessage() . ' Order updation error.');
            // }



}


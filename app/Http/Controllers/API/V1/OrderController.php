<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Resources\OrderMasterResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItems;
use App\Models\OrderMaster;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class OrderController extends Controller
{

    public function userOrders(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if ($user) {
                $orders = OrderMaster::where(['user_id' => $user->id])->get();
                if ($orders) {
                    return response()->json([
                        'status' => true,
                        'orders' => OrderMasterResource::collection($orders),

                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'No data available',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function userOrderAdd(Request  $request)
    {
        try {

            $credentials = FacadesValidator::make($request->all(), [
                'total_amount' => 'required|numeric',
                'total_item' => 'required|numeric',
                'payment' => 'required|in:COD,DEBIT,CREDIT,UPI',
                'address' => 'required|exists:user_addresses,id',

                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.sub_total' => 'required|numeric',
            ]);
            if ($credentials->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => "Validation Error",
                    "errors" => $credentials->errors(),
                ]);
            }

            $user = Auth::guard('api')->user();

            if ($user) {
                try {


                    DB::beginTransaction();
                    // Create a new order instance
                    $order = new OrderMaster();
                    $order->user_id = $user->id;
                    $order->order_number = $this->generateOrderNumber();
                    $order->grand_total = $request->input('total_amount');
                    $order->total_item = $request->input('total_item');
                    $order->payment = $request->input('payment');
                    $order->delivery_address_id = $request->input('address');
                    $order->status = 'pending';
                    $order->save();

                    // Create order items
                    $items = $request->input('items');
                    if (!empty($items)) {
                        if (!is_array($items)) {
                            // If $items is not an array, convert it to an array
                            $items = [$items];
                        }

                        foreach ($items as $item) {
                            // Create order items
                            $orderItem = new OrderDetail();
                            $orderItem->order_id = $order->id;
                            $orderItem->product_id = $item['product_id'];
                            $orderItem->product_qty = $item['quantity'];
                            $orderItem->sub_total = $item['sub_total'];
                            $orderItem->status = 'pending';
                            $orderItem->save();
                        }
                    }
                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'message' => 'Order placed successfully.',
                        'order' => $order->order_number,
                    ], 201);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to create product. Try again Later',
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    private function generateOrderNumber()
    {
        $orderNumber = uniqid();
        // Check if the generated order number already exists in the table
        $existingOrder = OrderMaster::where('order_number', $orderNumber)->first();
        // If an order with the same order number exists, generate a new one recursively
        if ($existingOrder) {
            return $this->generateOrderNumber();
        }
        return $orderNumber;
    }

    public function userOrderCancel(Request  $request, $orderId)
    {
        try {

            $order= OrderDetail::where('id', $orderId)->first();
            $MainOrder = OrderMaster::where('id', $order->order_id)->first();

            if (!$order||!$MainOrder) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found.',
                ]);
            }

            // Check if the order is already canceled
            if ($order->status === 'canceled') {
                return response()->json([
                    'status' => false,
                    'message' => 'Order is already canceled.',
                ]);
            }

            // Update the order status to canceled
            $order->status = 'cancelled';
            $MainOrder->grand_total = $MainOrder->grand_total - $order->sub_total;
            if($order->save()&&$MainOrder->save()){

                return response()->json([
                    'status' => true,
                    'message' => 'Order cancelled successfully.',
                    'order' => $order,
                ], 200);

            }else{

                return response()->json([
                    'status' => true,
                    'message' => 'Something went wrong to Cancel Order.',
                ]);

            }



        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    

}

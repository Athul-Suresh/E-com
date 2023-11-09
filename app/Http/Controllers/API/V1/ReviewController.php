<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{

    public function reviews(Request $request, $product)
    {

        $productReview = ProductReview::where(['product_id' => $product])->with(['product', 'user'])->get();
        if (!is_null($productReview)) {
            return response()->json(['status' => true, 'data' => $productReview]);
        }
        return response()->json(['status' => false, 'message' => 'No Reviews Yet', 'data' => $productReview]);
    }

    public function userReviews(Request $request, $product)
    {

        $productReview = ProductReview::where(['product_id' => $product, 'user_id' => Auth::guard('api')->user()->id])->with(['product', 'user'])->first();
        if (!is_null($productReview)) {
            return response()->json(['status' => true, 'data' => $productReview]);
        }
        return response()->json(['status' => false, 'message' => 'No Reviews Yet', 'data' => $productReview]);
    }


    public function addReview(Request $request)
    {


        try {

            $validator = Validator::make($request->all(), [
                'product' => 'required|exists:products,id',
                'rating' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
                'comment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['status'=>false,'message' => 'Invalid input data', 'error' => $validator->errors()]);
            }

            $productId = $request->input('product');
            $rating = $request->input('rating');
            $comment = $request->input('comment');

            $productReview = ProductReview::where(['product_id' => $productId, 'user_id' => Auth::guard('api')->user()->id])->first();
            
            if (is_null($productReview)) {
                $productReview = new ProductReview();
                $productReview->user_id = (int) Auth::guard('api')->user()->id;
                $productReview->product_id = $productId;
                $productReview->rating = $rating;
                $productReview->comment = $comment;
                $productReview->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Product Review added successfully',
                    'data' => $productReview,
                ]);
            } else {

                $productReview->user_id = (int) Auth::guard('api')->user()->id;
                $productReview->product_id = $productId;
                $productReview->rating = $rating;
                $productReview->comment = $comment;
                $productReview->save();

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Product Review Updated successfully'
                    ]
                );
            }
        } catch (\Throwable $th) {
            // return response()->json(['status' =>false,'message' =>$th->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $th->getMessage()], 500);
        }
    }

    public function updateReview(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product' => 'required|exists:products,id',
                'rating' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
                'comment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['status'=>false,'message' => 'Invalid input data', 'error' => $validator->errors()]);
            }

            $productId = $request->input('product');
            $rating = $request->input('rating');
            $comment = $request->input('comment');

            $productReview = ProductReview::where(['product_id' => $productId, 'user_id' => Auth::guard('api')->user()->id])->first();

            if (is_null($productReview)) {
                return response()->json(['status' => false, 'message' => 'Product Review not found']);
            }

            $productReview->rating = $rating;
            $productReview->comment = $comment;
            $productReview->save();

            return response()->json(['status' => true, 'message' => 'Product Review updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $th->getMessage()], 500);
        }
    }


    public function removeReview(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product' => 'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status'=>false,'message' => 'Invalid input data', 'error' => $validator->errors()]);
            }

            $productId = $request->input('product');

            $productReview = ProductReview::where(['product_id' => $productId, 'user_id' => Auth::guard('api')->user()->id])->first();

            if (is_null($productReview)) {
                return response()->json(['status' => false, 'message' => 'Product Review not found']);
            }

            $productReview->delete();

            return response()->json(['status' => true, 'message' => 'Product Review removed successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $th->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductCart;
use App\Http\Resources\ProductCart as ResourcesProductCart;
use App\Http\Resources\UserAddressResource;
use App\Mail\VerifyEmail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderMaster;
use App\Models\Product;
use App\Models\State;
use App\Models\User;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;


class UserController extends Controller
{

    private $status_200 = 200;
    public function userSignUp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "phone" => "nullable|max:25"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "validation_error",
                "errors" => "All fields are required" . $validator->errors()

            ], $this->status_200);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->save();
        $token = $user->createToken('API_Token')->accessToken;
        if ($request->input('offline')) {
            $response = $this->updateOfflineCart($user, $request);
            if (!$response['status']) {
                return response()->json($response);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Successfully Registered',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], $this->status_200);
    }

    public function userSignIn(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $accessToken = Auth::guard('web')->user()->createToken('API_Token')->accessToken;
            if ($request->input('offline')) {
                $response = $this->updateOfflineCart(null, $request);
                if (!$response['status']) {
                    return response()->json($response);
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Logged in successfully',
                'authorisation' => [
                    'token' => $accessToken,
                    'type' => 'bearer',
                ]
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Invalid Login Credentials',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'Invalid login credentials',
        ]);
    }

    public function userDetails(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if ($user) {
                return response()->json([
                    'status' => true,
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function userStates(Request $request)
    {
        try {
            $states = State::all();
            if ($states) {
                return response()->json([
                    'status' => true,
                    'states' => $states,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No data available',
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

    public function userProfile(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();

            if ($user) {
                $validator = Validator::make($request->all(), [
                    "name" => "required|max:255",
                    "email" => "required|email|unique:users,email," . $user->id,
                    "password" => "required|confirmed", //password_confirmation
                    "phone" => "nullable|max:25"
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        "status" => false,
                        "message" => "Validation Error",
                        "errors" => $validator->errors(),
                    ], $this->status_200);
                }

                $user = User::find($user->id);
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->password = Hash::make($request->input('password'));
                $user->phone = $request->input('phone');
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Profile Updated',
                    'user' => $user,
                ], $this->status_200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Login to update profile',
                    'user' => null,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal server error', 'er' => $th->getMessage()], 500);
        }
    }

    public function userAddress(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $address = UserAddress::where(['user_id' => $user->id, 'status' => 1])->get();
            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'data' => UserAddressResource::collection($address),
            ], $this->status_200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function userAddressSingle(Request $request, $id)
    {
        try {
            $user = Auth::guard('api')->user();
            $address = UserAddress::where(['user_id' => $user->id, 'id' => $id])->first();
            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'data' => new UserAddressResource($address),
            ], $this->status_200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function userAddressAdd(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if ($user) {
                $validator = Validator::make($request->all(), [
                    "name" => "required|max:255",
                    "locality" => "required|max:255",
                    "phone" => "required|max:10",
                    "pincode" => "required|max:6",
                    "alternative_phone" => "nullable|max:10",
                    "landmark" => "nullable",
                    "city" => "required",
                    "state" => "required",
                    "address" => "required",
                    "address_type" => "required|in:1,2",
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        "status" => false,
                        "message" => "Validation Error",
                        "errors" => $validator->errors()
                    ], $this->status_200);
                }

                $userAddress = new UserAddress();
                $userAddress->name = $request->input('name');
                $userAddress->locality = $request->input('locality');
                $userAddress->phone_1 = $request->input('phone');
                $userAddress->phone_2 = $request->input('alternative_phone');
                $userAddress->pincode = $request->input('pincode');
                $userAddress->address = $request->input('address');
                $userAddress->landmark = $request->input('landmark');
                $userAddress->city = $request->input('city');
                $userAddress->address_type = $request->input('address_type');
                $userAddress->user_id = $user->id;
                $userAddress->state_id = $request->input('state');

                if ($userAddress->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Address added successfully',
                        'user' => $userAddress,
                    ], $this->status_200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong while adding the address',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal server error', 'test' => $th->getMessage()], 500);
        }
    }

    public function userAddressUpdate(Request $request, $id)
    {
        try {
            $user = Auth::guard('api')->user();
            if ($user) {
                $userAddress = UserAddress::where('id', $id)->where('user_id', $user->id)->first();
                if (!$userAddress) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Address not found',
                    ]);
                }

                $validator = Validator::make($request->all(), [
                    "name" => "required|max:255",
                    "locality" => "required|max:255",
                    "phone" => "required|max:10",
                    "pincode" => "required|max:6",
                    "alternative_phone" => "nullable|max:10",
                    "landmark" => "nullable",
                    "city" => "required",
                    "state" => "required",
                    "address" => "required",
                    "address_type" => "required|in:1,2",
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        "status" => false,
                        "message" => "validation_error",
                        "errors" => $validator->errors()
                    ], $this->status_200);
                }
                $userAddress->name = $request->input('name');
                $userAddress->locality = $request->input('locality');
                $userAddress->phone_1 = $request->input('phone');
                $userAddress->phone_2 = $request->input('alternative_phone');
                $userAddress->pincode = $request->input('pincode');
                $userAddress->address = $request->input('address');
                $userAddress->landmark = $request->input('landmark');
                $userAddress->city = $request->input('city');
                $userAddress->address_type = $request->input('address_type');
                $userAddress->state_id = $request->input('state');

                if ($userAddress->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Address updated successfully',
                        'user' => $userAddress,
                    ], $this->status_200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong while updating the address',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function userAddressRemove(Request $request, $id)
    {
        try {
            $user = Auth::guard('api')->user();
            if ($user) {
                $userAddress = UserAddress::where('id', $id)->where('user_id', $user->id)->first();
                if (!$userAddress) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Address not found',
                    ]);
                }
                $userAddress->status = 0;
                if ($userAddress->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Address removed successfully',

                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong while removing the address',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }


    public function userSocial(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required|max:255",
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "validation_error",
                "errors" => "All fields are required"

            ], $this->status_200);
        }

        $user = User::where(['email' => $request->input('email'), 'type' => 'S'])->first();
        if (is_null($user)) {
            // Create a new user and Login
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->type = "S";
            $user->password = Hash::make($request->input('password'));
            $user->save();
            $token = $user->createToken('API_Token')->accessToken;
            if ($request->input('offline')) {
                $response = $this->updateOfflineCart($user, $request);
                if (!$response['status']) {
                    return response()->json($response);
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'logged in successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], $this->status_200);
        } else {
            // Login with the existing user
            // dd(['password' =>Hash::check($request->input('password'),'$2y$10$VZZIa6gpRcDSfA8AmhXbv.xRxk/zAwpHJ0ttUEBXI0JMD/Tctiyv6')]);
            try {
                if (Auth::guard('web')->attempt(
                    ['email' => $request->input('email'), 'password' => $request->input('password')]
                )) {
                    $accessToken = Auth::guard('web')->user()->createToken('API_Token')->accessToken;
                    if ($request->input('offline')) {
                        $response = $this->updateOfflineCart(Null, $request);
                        if (!$response['status']) {
                            return response()->json($response);
                        }
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Logged in successfully',
                        'authorisation' => [
                            'token' => $accessToken,
                            'type' => 'bearer',
                        ]
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid Login Credentials',
                    ]);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => 'Internal Server Error',
                ]);
            }
        }
    }


    private function sendmail($from = null, $data = null)
    {
        try {
            Mail::to($from)->send(new VerifyEmail($data));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function userForgot(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
        ]);

        $token = Crypt::encryptString($request->email);
        $resetLink = url(env('API_URL') . 'reset-password?token=' . $token);

        $user = User::where(['email' => $request->input('email')])->first();

        if (empty($user)) {
            return response()->json([
                "status" => false,
                "message" => "Email is not valid",
            ], $this->status_200);
        }




        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $mailData = [
            'resetLink' => $resetLink,
            'email' => $request->email,
        ];

        // Send the email
        if ($this->sendMail($request->email, $mailData)) {
            return response()->json([
                'status' => true,
                'message' => 'Reset password link sent successfully',
            ], $this->status_200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error',
            ]);
        }
    }


    public function userResetLinkVerify(Request $request)
    {
        $email = DB::table('password_reset_tokens')->select('email')->where(['token' => $request->token])->first();
        if (!empty($email)) {
            return response()->json([
                'status' => true,
                $email
            ]);
        } else {
            return  response()->json([
                'status' => false,
                'message' => "Unauthenticated"
            ]);
        }
    }


    public function userResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "validation_error",
                "errors" => "All fields are required"

            ], $this->status_200);
        }
        $user = User::where(['email' => $request->input('email')])->first();
        if (is_null($user)) {
            return  response()->json([
                'status' => false,
                'message' => "Unauthenticated"
            ]);
        }
        $user->name = $user->name;
        $user->email =  $user->email;
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json([
            'status' => true,
            'message' => 'Password Reset Successfully, please login to continue',
        ], $this->status_200);
    }


    private function updateOfflineCart($user = Null, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cart_items' => 'required|array',
                'cart_items.*.item' => 'required|exists:products,id',
                'cart_items.*.quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return ['status' => false, 'message' => $validator->errors()];
            }

            $user = $user == Null ? Auth::guard('web')->user() : $user;
            if (is_null($user)) {
                return ['status' => false, 'message' => 'Invalid user'];
            }

            $cartItems = $request->input('cart_items');

            foreach ($cartItems as $cartItem) {
                $productCart = $user->productCarts()
                    ->where('product_id', $cartItem['item'])
                    ->first();

                if (is_null($productCart)) {
                    $productCart = new ProductCart();
                    $productCart->user_id = $user->id;
                    $productCart->product_id = $cartItem['item'];
                    $productCart->quantity = $cartItem['quantity'];
                    $productCart->save();
                } else {
                    $productCart->quantity += $cartItem['quantity'];
                    $productCart->save();
                }
            }

            return ['status' => true, 'message' => 'Offline cart updated successfully'];
        } catch (\Exception $exception) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }
}

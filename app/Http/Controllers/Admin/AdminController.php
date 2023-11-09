<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderMaster;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    private $user;

    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    private function authorizeUser(): void
    {
        if (is_null($this->user) || !$this->user->can('dashboard.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        }
    }
    public function dashboard()
    {
        $this->authorizeUser();
        $total_roles = $this->getTotalRoles();
        $total_admins = $this->getTotalAdmins();
        $total_permissions = $this->getTotalPermissions();

        $activeUsers = $this->getActiveUsers();
        $recentOrders = $this->getRecentOrders();
        $totalSales = $this->getTotalSales();
        $todaysOrders = $this->todaysOrders();
        $allTimeOrders = $this->allTimeOrders();

        $latestOrders = OrderMaster::with(['details', 'user'])->orderBy('created_at', 'desc')->limit(10)->get();
        $latestUsers  =  User::with("orders")->orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.dashboard.home.index', [
            'activeUsers' => $activeUsers,
            'recentOrders' => $recentOrders,
            'totalSales' => $totalSales,
            'todaysOrders' => $todaysOrders,
            'allOrders' => $allTimeOrders,
            'latestOrders' => $latestOrders,
            'latestUsers' => $latestUsers,
        ]);
    }


    private function getTotalSales()
    {
        return OrderMaster::where('status', 'completed')->sum('grand_total');
    }





    private function getRecentOrders()
    {

        return OrderMaster::orderBy('id', 'desc')->get();
    }
    private function getActiveUsers(): int
    {

        return User::count();
    }

    private function getTotalRoles(): int
    {
        return Role::count();
    }

    private function getTotalAdmins(): int
    {
        return Admin::count();
    }

    private function getTotalPermissions(): int
    {
        return Permission::count();
    }
    public function profile()
    {
        $this->authorizeUser('profile', 'view');
        $logs = []; //\App\Models\LogActivity::logActivityLists();
        return view('admin.dashboard.admin.profile', ['data' => $this->user, 'log' => $logs]);
    }

    public function profileCreate(Request $request)
    {
        $this->authorizeUser('profile', 'edit');
        $user = $this->user;
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'username' => 'required',
            'password' => 'nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->username = $validatedData['username'];
        if ($validatedData['password']) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Handle the user's profile image
        if ($request->hasFile('image')) {
            // Delete the old profile image if it exists
            // if ($user->image) {
            //     Storage::delete('public/uploads/admin/profile' . $user->image);
            // }

            // $image = $request->file('image');
            // $filename = $user->id . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            // $image->storeAs('public/uploads/admin/profile', $filename);
            // $user->image = $filename;

            $image = $request->file('image');

            // Generate a unique filename for the image
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $user->image = $imageName;
            // Store the image in the 'public' disk under the 'uploads' directory
            $imagePath = $image->storeAs('uploads/admin/profile', $imageName, 'public');
            // Optionally, you can set the visibility of the stored file to public
            Storage::disk('public')->setVisibility($imagePath, 'public');
        }
        if ($user->save()) {
            // \LogActivity::addToLog($user->name.' Updated profile details');
            return redirect()
                ->route('admin.profile')
                ->with('success', 'Profile updated successfully');
        }
    }

    public function todaysOrders(): int
    {
        $today = Carbon::today();

        return OrderMaster::whereDate('created_at', $today)->count();
    }


    public function allTimeOrders(): int
    {
        return OrderMaster::where("status", "completed")->count();
    }



    public function getOrderCounts()
    {
        $completedCount = OrderMaster::where('status', 'completed')->count();
        $cancelledCount = OrderMaster::where('status', 'cancelled')->count();
        $pendingCount = OrderMaster::where('status', 'pending')->count();
        $processingCount = OrderMaster::where('status', 'processing')->count();

        // Return the counts as JSON response
        return response()->json([
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount,
            'pendingCount' => $pendingCount,
            'processingCount' => $processingCount,
        ]);
    }


    public function getPaymentsCounts()
    {
        $codCount = OrderMaster::where('payment', 'COD')->count();
        $cardCount = OrderMaster::where('payment', 'CREDIT')->count();
        $debitCount = OrderMaster::where('payment', 'DEBIT')->count();
        $upiCount = OrderMaster::where('payment', 'UPI')->count();

        // Return the counts as JSON response
        return response()->json([
            'COD' => $codCount,
            'CARD' => $cardCount,
            'DEBIT' => $debitCount,
            'UPI' => $upiCount,
        ]);
    }
}

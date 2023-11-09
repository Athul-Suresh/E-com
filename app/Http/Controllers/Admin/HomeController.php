<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    private $user;

    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index()
    {
    
        $this->authorizeUser();
        $total_roles = $this->getTotalRoles();
        $total_admins = $this->getTotalAdmins();
        $total_permissions = $this->getTotalPermissions();
        $activeUsers = $this->getActiveUsers();
        dd( $activeUsers);
        return view('admin.dashboard.home.index',['activeUsers'=>$activeUsers]);
    }

    private function authorizeUser(): void
    {
        if (is_null($this->user) || !$this->user->can('dashboard.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        }
    }

    private function getTotalRoles(): int
    {
        return Role::count();
    }

    private function getTotalAdmins(): int
    {
        return Admin::count();
    }

    private function getActiveUsers(): int
    {

        return User::count();
    }

    private function getTotalPermissions(): int
    {
        return Permission::count();
    }
}

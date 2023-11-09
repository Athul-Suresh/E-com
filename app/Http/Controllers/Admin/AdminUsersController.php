<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUsersController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    private function authorizeUser($permission,$type): void
    {
        if (is_null($this->user) || !$this->user->can($permission.'.'.$type)) {
            abort(403, 'Sorry !! You are Unauthorized!');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorizeUser('admin','view');
        $admins = Admin::all();
        return view('admin.dashboard.usermanagement.admins', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeUser('admin','create');
        $roles  = Role::all();
        return view('admin.dashboard.usermanagement.adminsCreate', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeUser('admin','create');
         // Validation Data
         $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:admins',
            'username' => 'required|max:100|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create New Admin
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->is_active = True;
        $admin->save();

        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', 'Admin has been created !!');
        return redirect()->route('admin.admins.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizeUser('admin','edit');
        $admin = Admin::findOrfail($id);
        $roles  = Role::all();
        return view('admin.dashboard.usermanagement.adminsEdit', compact('roles','admin'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeUser('admin','edit');
 // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        if ($id === 1) {
            session()->flash('error', 'Sorry !! You are not authorized to update this Admin as this is the Super Admin.');
            return back();
        }

        // Create New Admin
        $admin = Admin::find($id);
        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        $admin->roles()->detach();
        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', 'Admin has been updated !!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeUser('admin','delete');
    }
}

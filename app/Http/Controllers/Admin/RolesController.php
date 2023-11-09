<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RolesController extends Controller
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
        $this->authorizeUser('role','view');
        $roles = Role::all();
        return view('admin.dashboard.usermanagement.roles', compact('roles'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeUser('role','create');
        $all_permissions  = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('admin.dashboard.usermanagement.rolesCreate', compact('all_permissions', 'permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeUser('role','create');
        $request->validate([
            'name' => 'required|max:100|unique:roles',
        ], [
            'name.required' => 'Please provide a role name.',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'admin',
        ]);

        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        session()->flash('success', 'Role has been created!');
        return redirect()->route('admin.roles.index');
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
        $this->authorizeUser('role','edit');

        try {
            $role = Role::findById($id, 'admin');
            $all_permissions = Permission::all();
            $permission_groups = User::getpermissionGroups();
            return view('admin.dashboard.usermanagement.rolesEdit', compact('role', 'all_permissions', 'permission_groups'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found.'], 404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeUser('role','edit');

        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        if ($id === 1) {
            session()->flash('error', 'Sorry !! You are not authorized to edit this role !');
            return back();
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        $role = Role::findById($id, 'admin');
        // $permission = Permission::findById($id, 'admin');

        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($permissions);
        }

        session()->flash('success', 'Role has been updated !!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DataTables;
class CustomerController extends Controller
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
        $this->authorizeUser('customer', 'view');
        $customers = User::all();

        if($request->ajax()){
            $data = User::latest()->with('orders')->get();

            return Datatables::of($data)
                ->make(true);
        }

         return view('admin.dashboard.customer.index', compact('customers'));

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorizeUser('customer', 'edit');
        $customer = User::findOrfail($id);
        return view('admin.dashboard.customer.edit', compact('customer'));
    }

    public function update(Request $request,$id)
    {
        $this->authorizeUser('customer', 'edit');
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "name" => "required|max:255",
            "email" => "required|email|unique:users,email," . $id,
            "phone" => "nullable|max:25"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->save();
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');

    }


}

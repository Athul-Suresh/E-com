<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;


class AuthController extends Controller
{


    public function showLoginForm() {
        return view('admin.auth.login');
    }

    /**
     * Attempt to log in the admin user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ], [
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Please enter your password.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            session()->flash('success', 'Successfully logged in!');
            return redirect()->route('admin.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Invalid email and password combination.',
        ]);
    }
    /**
     * Log out the admin user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

}


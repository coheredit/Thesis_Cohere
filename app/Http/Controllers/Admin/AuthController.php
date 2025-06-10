<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showSignupForm()
    {
        return view('admin.signup');
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:admin,email',
            'f_name' => 'required|string|max:50',
            'l_name' => 'required|string|max:50',
            'phone' => 'required|digits:11',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'profile_picture' => 'required|in:default.png,boy.png,boy1.png,boy2.png,girl.png,girl1.png,girl2.png', // ✅ Added validation
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        Admin::create([
            'email' => $request->email,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'profile_picture' => $request->profile_picture, // ✅ Added this
        ]);

        return redirect()->route('admin.login')->with('success', 'Account created successfully. Please log in.');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            return redirect()->route('admin.home');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
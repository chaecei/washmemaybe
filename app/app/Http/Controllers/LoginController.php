<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // Make sure resources/views/login.blade.php exists
    }

    public function login(Request $request)
    {
        // Validate mobile number and password
        $request->validate([
            'mobile_number' => 'required',
            'password' => 'required',
        ]);

        // Attempt login using mobile_number instead of email
        if (Auth::attempt([
            'mobile_number' => $request->mobile_number,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Failed login
        return back()->withErrors([
            'mobile_number' => 'Invalid credentials.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog; // <-- IDINAGDAG: Para sa Activity Log
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // <-- IDINAGDAG: I-record ang successful login
            ActivityLog::record('Login', 'User logged in successfully: ' . $user->email);

            if ($user->role === 'admin') {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/');
        }

        // <-- IDINAGDAG: I-record ang failed login attempt (Security Feature)
        ActivityLog::record('Failed Login', 'Failed login attempt for email: ' . $request->email);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // <-- IDINAGDAG: I-record ang logout bago mag-logout talaga
        if (Auth::check()) {
            ActivityLog::record('Logout', 'User logged out: ' . Auth::user()->email);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog; // <-- IDINAGDAG: Para sa Activity Log
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        // LINISIN ANG LUMANG ERROR MESSAGE PARA MALINIS ANG REGISTER PAGE
        session()->forget('error');
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => [
                'required', 
                'string', 
                'min:8', 
                'regex:/[a-zA-Z]/',        // Must have at least 1 letter
                'regex:/[0-9]/',           // Must have at least 1 number
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // Must have at least 1 special character
                'confirmed'
            ],
        ], [
            'password.regex' => 'Ang password ay dapat magkaroon ng kahit 1 letra, 1 numero, at 1 special character (!@#$%^&*).',
            'password.confirmed' => 'Hindi magtutugma ang password at confirm password.',
        ]);

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'is_verified' => false,
            'wallet_balance' => 200.00, // Welcome Bonus Tokens
        ]);

        // <-- IDINAGDAG: I-record ang registration sa Activity Log
        ActivityLog::record('Register', 'New user registered: ' . $user->email . ' and received 200 Welcome Tokens.');

        // Send OTP Email
        Mail::to($user->email)->send(new OtpMail($otp, $user->name));

        // Store user ID in session for verification
        session(['otp_user_id' => $user->id]);
        session()->save();

        return redirect()->route('otp.verify')->with('success', 'Registration successful! You received 200 Welcome Tokens in your account. Please verify your OTP.');
    }
}
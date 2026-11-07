<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
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
                'regex:/[a-zA-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
                'confirmed',
            ],
        ], [
            'password.regex' => 'Ang password ay dapat magkaroon ng kahit 1 letra, 1 numero, at 1 special character (!@#$%^&*).',
            'password.confirmed' => 'Hindi magtutugma ang password at confirm password.',
        ]);

        $otp = (string) random_int(100000, 999999);
        $pendingRegistration = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'otp_hash' => hash_hmac('sha256', $otp, config('app.key')),
            'expires_at' => now()->addMinutes(10)->timestamp,
        ];

        Mail::to($pendingRegistration['email'])->send(
            new OtpMail($otp, $pendingRegistration['name'])
        );

        session([
            'pending_registration' => $pendingRegistration,
        ]);

        return redirect()->route('otp.verify')->with(
            'success',
            'We sent a verification code to your email. Verify it within 10 minutes to create your account.'
        );
    }
}

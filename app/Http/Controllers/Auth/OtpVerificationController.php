<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Carbon\Carbon;

class OtpVerificationController extends Controller
{
    // Ipakita ang OTP Verification Form
    public function showVerifyForm()
    {
        // Check kung may session
        if (!session()->has('otp_user_id')) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        $user = User::find(session('otp_user_id'));
        if (!$user) {
            return redirect()->route('register')->with('error', 'User not found. Please register again.');
        }

        return view('auth.verify-otp');
    }

    // I-verify ang OTP
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        // Get user ID from session
        $userId = session('otp_user_id');
        
        // Debug: Check if session exists
        if (!$userId) {
            return redirect()->route('register')->with('error', 'Session lost. Please register again.');
        }

        // Find user
        $user = User::find($userId);

        // Check if user exists and has OTP
        if (!$user || !$user->otp) {
            return redirect()->route('register')->with('error', 'Invalid session or OTP already used. Please register again.');
        }

        // Check if OTP expired
        if (!$user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at)) {
            // Delete expired user
            $user->delete();
            return redirect()->route('register')->with('error', 'OTP expired. Please register again.');
        }

        // Verify OTP
        if ($user->otp === $request->otp) {
            // Success!
            $user->is_verified = true;
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            // Login user (AUTO LOGIN - hindi na kailangan mag-login ulit)
            Auth::login($user, true);
            
            // Clear OTP session
            session()->forget('otp_user_id');
            session()->regenerate();
            session()->save();

            // Redirect to Home/Dashboard - naka-login na!
            return redirect()->route('home')->with('success', 'Email verified successfully! Welcome to Pureblooms.');
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }

    // Resend OTP
    public function resendOtp()
    {
        $userId = session('otp_user_id');
        $user = User::find($userId);
        
        if ($user && $user->otp) {
            $otp = rand(100000, 999999);
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
            return back()->with('success', 'New OTP sent to your email!');
        }
        
        return redirect()->route('register')->with('error', 'Session expired. Please register again.');
    }
}
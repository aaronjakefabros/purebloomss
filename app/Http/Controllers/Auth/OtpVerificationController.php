<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
    public function showVerifyForm()
    {
        $pendingRegistration = session('pending_registration');

        if (! is_array($pendingRegistration)) {
            return redirect()->route('register')->with('error', 'Your registration session expired. Please register again.');
        }

        if ($this->otpHasExpired($pendingRegistration)) {
            session()->forget('pending_registration');

            return redirect()->route('register')->with('error', 'Your OTP expired. Please register again.');
        }

        return view('auth.verify-otp', [
            'email' => $pendingRegistration['email'],
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $pendingRegistration = session('pending_registration');

        if (! is_array($pendingRegistration)) {
            return redirect()->route('register')->with('error', 'Your registration session expired. Please register again.');
        }

        if ($this->otpHasExpired($pendingRegistration)) {
            session()->forget('pending_registration');

            return redirect()->route('register')->with('error', 'Your OTP expired. Please register again.');
        }

        $submittedOtpHash = hash_hmac('sha256', (string) $request->input('otp'), config('app.key'));

        if (
            ! isset($pendingRegistration['otp_hash'])
            || ! is_string($pendingRegistration['otp_hash'])
            || ! hash_equals($pendingRegistration['otp_hash'], $submittedOtpHash)
        ) {
            return back()->with('error', 'Invalid OTP. Please try again.');
        }

        if (User::where('email', $pendingRegistration['email'])->exists()) {
            session()->forget('pending_registration');

            return redirect()->route('register')->with('error', 'An account with this email already exists. Please sign in.');
        }

        $user = User::create([
            'name' => $pendingRegistration['name'],
            'email' => $pendingRegistration['email'],
            'phone' => $pendingRegistration['phone'],
            'password' => $pendingRegistration['password'],
            'role' => 'customer',
            'is_verified' => true,
            'email_verified_at' => now(),
            'wallet_balance' => 200.00,
        ]);

        ActivityLog::record(
            'Register',
            'New user registered: '.$user->email.' and received 200 Welcome Tokens.'
        );

        Auth::login($user, true);

        session()->forget('pending_registration');
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Email verified successfully! Welcome to Pureblooms.');
    }

    public function resendOtp()
    {
        $pendingRegistration = session('pending_registration');

        if (! is_array($pendingRegistration)) {
            return redirect()->route('register')->with('error', 'Your registration session expired. Please register again.');
        }

        if ($this->otpHasExpired($pendingRegistration)) {
            session()->forget('pending_registration');

            return redirect()->route('register')->with('error', 'Your OTP expired. Please register again.');
        }

        $otp = (string) random_int(100000, 999999);
        $pendingRegistration['otp_hash'] = hash_hmac('sha256', $otp, config('app.key'));
        $pendingRegistration['expires_at'] = now()->addMinutes(10)->timestamp;

        Mail::to($pendingRegistration['email'])->send(
            new OtpMail($otp, $pendingRegistration['name'])
        );

        session(['pending_registration' => $pendingRegistration]);

        return back()->with('success', 'A new verification code was sent to your email.');
    }

    private function otpHasExpired(array $pendingRegistration): bool
    {
        if (! isset($pendingRegistration['expires_at']) || ! is_numeric($pendingRegistration['expires_at'])) {
            return true;
        }

        return Carbon::now()->greaterThan(Carbon::createFromTimestamp((int) $pendingRegistration['expires_at']));
    }
}

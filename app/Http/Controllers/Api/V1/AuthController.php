<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use \App\Traits\ApiResponse;

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt(str()->random(16)), // random password since auth is OTP based
            'role' => 'user',
            'is_active' => true,
        ]);

        $otp = rand(100000, 999999);

        \App\Models\OtpVerification::create([
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'is_used' => false,
        ]);

        Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));

        return $this->success('User registered successfully. OTP sent to email.');
    }

    public function loginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            // For security, don't reveal if user exists or not, just return success.
            // But for this app's logic, let's create a user if they don't exist?
            // The prompt doesn't specify registration. We'll return an error if user doesn't exist.
            return $this->error('User not found with this email.', null, 404);
        }

        $otp = rand(100000, 999999);

        \App\Models\OtpVerification::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'is_used' => false,
        ]);

        Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));

        return $this->success('OTP sent successfully to your email.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $verification = \App\Models\OtpVerification::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return $this->error('Invalid or expired OTP.', null, 401);
        }

        $verification->update(['is_used' => true]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return $this->error('User not found.', null, 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success('Login successful.', [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}

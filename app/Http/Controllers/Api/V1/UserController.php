<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use \App\Traits\ApiResponse;

    public function profile(Request $request)
    {
        return $this->success('User profile retrieved successfully.', $request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('phone')) $user->phone = $request->phone;
        
        $user->save();

        $message = 'Profile updated successfully.';

        if ($request->has('email') && $request->email !== $user->email) {
            $otp = rand(100000, 999999);

            \App\Models\OtpVerification::create([
                'email' => $request->email,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
                'is_used' => false,
            ]);

            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));

            $message .= ' An OTP has been sent to your new email address. Please verify it to update your email.';
        }

        return $this->success($message, $user);
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'otp' => 'required|digits:6',
        ]);

        $otpRecord = \App\Models\OtpVerification::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return $this->error('Invalid or expired OTP.', 400);
        }

        $otpRecord->update(['is_used' => true]);

        $user = $request->user();
        $user->update(['email' => $request->email]);

        return $this->success('Email updated successfully.', $user);
    }
}

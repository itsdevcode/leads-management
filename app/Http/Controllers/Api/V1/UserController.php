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
            'avatar' => 'sometimes|string',
        ]);

        $user->update($request->only(['name', 'phone', 'avatar']));

        return $this->success('Profile updated successfully.', $user);
    }
}

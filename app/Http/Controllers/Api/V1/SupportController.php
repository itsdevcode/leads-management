<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    use \App\Traits\ApiResponse;

    public function info()
    {
        return $this->success('Support info retrieved successfully.', [
            'email' => 'support@leadpro.com',
            'phone' => '+1-800-LEAD-PRO',
            'working_hours' => 'Mon-Fri 9:00 AM - 5:00 PM EST'
        ]);
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $supportMessage = $request->user()->supportMessages()->create([
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return $this->success('Support message submitted successfully.', $supportMessage, 201);
    }
}

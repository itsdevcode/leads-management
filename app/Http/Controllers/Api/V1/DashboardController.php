<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use \App\Traits\ApiResponse;

    public function stats(Request $request)
    {
        $user = $request->user();

        $totalLeads = \App\Models\Lead::where('user_id', $user->id)->count();
        $convertedLeads = \App\Models\Lead::where('user_id', $user->id)->where('status', 'converted')->count();
        $pendingFollowUps = \App\Models\Reminder::where('user_id', $user->id)->where('status', 'pending')->count();
        $todaysActivities = \App\Models\Reminder::where('user_id', $user->id)
            ->whereDate('reminder_datetime', now()->toDateString())
            ->count();

        return $this->success('Dashboard stats retrieved successfully.', [
            'total_leads' => $totalLeads,
            'converted_leads' => $convertedLeads,
            'pending_followups' => $pendingFollowUps,
            'todays_activities' => $todaysActivities,
        ]);
    }

    public function upcomingFollowups(Request $request)
    {
        $user = $request->user();

        $upcoming = \App\Models\Reminder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('reminder_datetime', '>=', now())
            ->orderBy('reminder_datetime', 'asc')
            ->take(10)
            ->get();

        return $this->success('Upcoming follow-ups retrieved successfully.', $upcoming);
    }
}

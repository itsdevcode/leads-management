<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    use \App\Traits\ApiResponse;

    public function index(Request $request)
    {
        $query = \App\Models\Reminder::where('user_id', $request->user()->id)->with('lead');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('reminder_datetime', [$request->date_from, $request->date_to]);
        }

        $reminders = $query->orderBy('reminder_datetime', 'asc')->paginate($request->per_page ?? 15);

        return $this->success('Reminders retrieved successfully.', $reminders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'title' => 'required|string|max:255',
            'reminder_datetime' => 'required|date',
        ]);

        $lead = \App\Models\Lead::where('user_id', $request->user()->id)->findOrFail($request->lead_id);

        $reminder = $request->user()->reminders()->create([
            'lead_id' => $lead->id,
            'title' => $request->title,
            'reminder_datetime' => $request->reminder_datetime,
            'status' => 'pending',
        ]);

        return $this->success('Reminder created successfully.', $reminder, 201);
    }

    public function updateStatus(Request $request, string $id)
    {
        $reminder = \App\Models\Reminder::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        $reminder->update(['status' => $request->status]);

        return $this->success('Reminder status updated successfully.', $reminder);
    }
}

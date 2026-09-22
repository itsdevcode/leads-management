<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    use \App\Traits\ApiResponse;

    public function index(Request $request)
    {
        $user = $request->user();
        $query = \App\Models\Lead::where('user_id', $user->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $leads = $query->paginate($request->per_page ?? 15);

        return $this->success('Leads retrieved successfully.', $leads);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|in:new,contacted,in_progress,qualified,lost,converted',
            'source' => 'nullable|string|max:255',
            'priority' => 'nullable|in:low,medium,high',
            'follow_up_date' => 'nullable|date',
        ]);

        $lead = $request->user()->leads()->create($request->all());

        return $this->success('Lead created successfully.', $lead, 201);
    }

    public function show(Request $request, string $id)
    {
        $lead = \App\Models\Lead::with(['notes' => function($q) {
                $q->latest();
            }, 'reminders' => function($q) {
                $q->where('status', 'pending')->where('reminder_datetime', '>=', now())->orderBy('reminder_datetime', 'asc');
            }])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return $this->success('Lead details retrieved successfully.', $lead);
    }

    public function update(Request $request, string $id)
    {
        $lead = \App\Models\Lead::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'contact_person' => 'sometimes|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'status' => 'sometimes|in:new,contacted,in_progress,qualified,lost,converted',
            'source' => 'nullable|string|max:255',
            'priority' => 'sometimes|in:low,medium,high',
            'follow_up_date' => 'nullable|date',
        ]);

        $lead->update($request->all());

        return $this->success('Lead updated successfully.', $lead);
    }

    public function updateStatus(Request $request, string $id)
    {
        $lead = \App\Models\Lead::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:new,contacted,in_progress,qualified,lost,converted',
        ]);

        $lead->update(['status' => $request->status]);

        return $this->success('Lead status updated successfully.', $lead);
    }

    public function destroy(Request $request, string $id)
    {
        $lead = \App\Models\Lead::where('user_id', $request->user()->id)->findOrFail($id);
        $lead->delete();

        return $this->success('Lead deleted successfully.');
    }
}

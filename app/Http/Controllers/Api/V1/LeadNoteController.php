<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeadNoteController extends Controller
{
    use \App\Traits\ApiResponse;

    public function store(Request $request, string $lead_id)
    {
        $lead = \App\Models\Lead::where('user_id', $request->user()->id)->findOrFail($lead_id);

        $request->validate([
            'content' => 'required|string',
        ]);

        $note = $lead->notes()->create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        $lead->increment('notes_count');

        return $this->success('Note added successfully.', $note, 201);
    }

    public function update(Request $request, string $id)
    {
        $note = \App\Models\LeadNote::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'content' => 'required|string',
        ]);

        $note->update(['content' => $request->content]);

        return $this->success('Note updated successfully.', $note);
    }

    public function destroy(Request $request, string $id)
    {
        $note = \App\Models\LeadNote::where('user_id', $request->user()->id)->findOrFail($id);
        
        $lead = $note->lead;
        $note->delete();
        $lead->decrement('notes_count');

        return $this->success('Note deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DraftController extends Controller
{
    /**
     * Store a newly created draft in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'to' => 'nullable|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'nullable|string',
            'body' => 'nullable|string',
            'attachments_metadata' => 'nullable|array',
        ]);

        $draft = $request->user()->drafts()->create($validated);

        return response()->json($draft, 201);
    }

    /**
     * Update the specified draft in storage.
     */
    public function update(Request $request, Draft $draft)
    {
        if ($draft->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'to' => 'nullable|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'nullable|string',
            'body' => 'nullable|string',
            'attachments_metadata' => 'nullable|array',
        ]);

        $draft->update($validated);

        return response()->json($draft);
    }
}

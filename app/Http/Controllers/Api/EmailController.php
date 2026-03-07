<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailResource;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $folder = $request->query('folder', 'INBOX');

        $emails = $request->user()->emails()
            ->where('folder', $folder)
            ->orderBy('sent_at', 'desc')
            ->paginate(50);

        return EmailResource::collection($emails);
    }

    /**
     * Check if a background sync has completed for a specific folder.
     */
    public function checkSyncStatus(Request $request)
    {
        $folder = $request->query('folder', 'INBOX');
        $userId = $request->user()->id;
        $key = "sync_completed_{$userId}_{$folder}";

        $completed = Cache::pull($key, false);

        return response()->json(['completed' => $completed]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Email $email)
    {
        // Verify ownership through account relationship
        if ($email->account->user_id !== Auth::id()) {
            abort(403);
        }

        return new EmailResource($email);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Serve an attachment file.
     */
    public function show(Attachment $attachment)
    {
        // Check if the authenticated user owns the email this attachment belongs to
        if ($attachment->email->user_id !== Auth::id()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->path, $attachment->filename, [
            'Content-Type' => $attachment->content_type,
        ]);
    }
}

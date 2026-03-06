<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use App\Mail\MailMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Send an email message.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'draft_id' => 'nullable|exists:drafts,id',
            'to' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string',
            'body' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $user = $request->user();
        $attachmentsData = [];

        // Handle temporary uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('temp_attachments', 'local');
                $attachmentsData[] = [
                    'path' => storage_path('app/' . $path),
                    'filename' => $file->getClientOriginalName(),
                    'content_type' => $file->getClientMimeType(),
                    'temp_path' => $path,
                ];
            }
        }

        $mailable = new MailMessage($validated['subject'], $validated['body'], $attachmentsData);

        $mail = Mail::to(explode(',', $validated['to']));

        if (!empty($validated['cc'])) {
            $mail->cc(explode(',', $validated['cc']));
        }

        if (!empty($validated['bcc'])) {
            $mail->bcc(explode(',', $validated['bcc']));
        }

        try {
            $mail->send($mailable);

            // Delete draft if it exists and belongs to the user
            if (!empty($validated['draft_id'])) {
                $draft = Draft::find($validated['draft_id']);
                if ($draft && $draft->user_id === Auth::id()) {
                    $draft->delete();
                }
            }

            return response()->json(['message' => 'Email sent successfully']);
        } finally {
            // Clean up temp files
            foreach ($attachmentsData as $data) {
                Storage::disk('local')->delete($data['temp_path']);
            }
        }
    }
}

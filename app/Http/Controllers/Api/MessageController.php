<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use App\Mail\MailMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        ]);

        $mailable = new MailMessage($validated['subject'], $validated['body']);

        $mail = Mail::to(explode(',', $validated['to']));

        if (!empty($validated['cc'])) {
            $mail->cc(explode(',', $validated['cc']));
        }

        if (!empty($validated['bcc'])) {
            $mail->bcc(explode(',', $validated['bcc']));
        }

        $mail->send($mailable);

        // Delete draft if it exists and belongs to the user
        if (!empty($validated['draft_id'])) {
            $draft = Draft::find($validated['draft_id']);
            if ($draft && $draft->user_id === Auth::id()) {
                $draft->delete();
            }
        }

        return response()->json(['message' => 'Email sent successfully']);
    }
}

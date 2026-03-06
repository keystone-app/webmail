<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use App\Mail\MailMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Webklex\IMAP\Facades\Client;

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

        $mailable = new MailMessage(
            $validated['subject'], 
            $validated['body'], 
            ['email' => $user->email, 'name' => $user->name ?? $user->email],
            $attachmentsData
        );

        // Dynamically configure the mailer with user credentials from session
        $password = session('imap_password');
        if ($password) {
            config([
                'mail.mailers.smtp.username' => $user->email,
                'mail.mailers.smtp.password' => $password,
            ]);
        }

        $mail = Mail::to(explode(',', $validated['to']));

        if (!empty($validated['cc'])) {
            $mail->cc(explode(',', $validated['cc']));
        }

        if (!empty($validated['bcc'])) {
            $mail->bcc(explode(',', $validated['bcc']));
        }

        try {
            $mail->send($mailable);

            // Save to IMAP Sent folder
            $this->saveToImapSent($user, $mailable);

            // Delete draft if it exists and belongs to the user
            if (!empty($validated['draft_id'])) {
                $draft = Draft::find($validated['draft_id']);
                if ($draft && $draft->user_id === Auth::id()) {
                    $draft->delete();
                }
            }

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            Log::error("Failed to send email for user {$user->id}: " . $e->getMessage(), [
                'exception' => $e,
                'to' => $validated['to'],
                'subject' => $validated['subject']
            ]);
            return response()->json(['message' => 'Failed to send email: ' . $e->getMessage()], 500);
        } finally {
            // Clean up temp files
            foreach ($attachmentsData as $data) {
                Storage::disk('local')->delete($data['temp_path']);
            }
        }
    }

    /**
     * Save the sent message to the user's IMAP Sent folder.
     */
    protected function saveToImapSent($user, $mailable)
    {
        $password = session('imap_password');
        Log::debug("Checking session for imap_password. Session ID: " . session()->getId() . ". Has password: " . ($password ? 'Yes' : 'No'));
        
        if (!$password) {
            Log::warning("Cannot save to IMAP Sent: imap_password not found in session for user {$user->id}");
            return;
        }

        try {
            $client = Client::account('default');
            $client->username = $user->email;
            $client->password = $password;
            $client->connect();

            $folders = $client->getFolders();
            $folderPaths = [];
            foreach ($folders as $folder) {
                $folderPaths[] = $folder->path;
            }
            Log::debug("Available IMAP folder paths for user {$user->id}: " . implode(', ', $folderPaths));

            // Try to find the Sent folder with common full paths or generic name
            $sentFolder = $client->getFolderByPath('INBOX.enviadas')
                ?? $client->getFolderByPath('INBOX.Sent')
                ?? $client->getFolderByPath('Sent')
                ?? $client->getFolderByPath('INBOX/Sent')
                ?? $client->getFolder('Sent')
                ?? $client->getFolder('Sent Messages');

            if ($sentFolder) {
                Log::debug("Found Sent folder: {$sentFolder->path}. Appending message...");
                $sentFolder->appendMessage($mailable->render());
                Log::info("Successfully saved message to IMAP Sent folder for user {$user->id}");
            } else {
                Log::warning("Could not find a Sent folder for user {$user->id} among: " . implode(', ', $folderPaths));
            }
        } catch (\Exception $e) {
            Log::error("Failed to save message to IMAP Sent folder for user {$user->id}: " . $e->getMessage());
        }
    }
}

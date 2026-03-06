<?php

namespace App\Services;

use App\Models\Email;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImapMessageRepository
{
    /**
     * Upsert an email record in the database.
     *
     * @param User $user
     * @param string $folder
     * @param array $details
     * @param string $body
     * @return Email
     */
    public function upsertEmail(User $user, string $folder, array $details, string $body): Email
    {
        return Email::updateOrCreate(
            [
                'user_id' => $user->id,
                'folder' => $folder,
                'imap_uid' => $details['imap_uid'],
            ],
            [
                'subject' => $details['subject'],
                'from' => $details['from'],
                'to' => $details['to'],
                'date' => $details['date'],
                'body' => $body,
                'is_read' => $details['is_read'],
            ]
        );
    }

    /**
     * Store attachments for an email.
     *
     * @param Email $email
     * @param User $user
     * @param array $attachments
     * @return void
     */
    public function storeAttachments(Email $email, User $user, array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $filename = $attachment['filename'];
            $contentType = $attachment['content_type'];
            $size = $attachment['size'];
            $contentId = $attachment['content_id'];
            $isInline = $attachment['is_inline'];
            $content = $attachment['content'];

            // Strip brackets from contentId if present
            $cleanContentId = str_replace(['<', '>'], '', (string)$contentId);

            $storagePath = "attachments/{$user->id}/{$email->id}";
            
            // Save to storage
            Storage::disk('public')->put("{$storagePath}/{$filename}", $content);

            $emailAttachment = $email->attachments()->updateOrCreate(
                ['content_id' => $contentId, 'filename' => $filename],
                [
                    'content_type' => $contentType,
                    'size' => $size,
                    'path' => "{$storagePath}/{$filename}",
                    'is_inline' => $isInline,
                ]
            );

            // If inline, try to replace cid in body
            if ($isInline && $cleanContentId) {
                $proxyUrl = route('attachments.show', $emailAttachment->id);
                Log::debug("Replacing cid:{$cleanContentId} with {$proxyUrl}");
                $email->body = str_replace("cid:{$cleanContentId}", $proxyUrl, $email->body);
                $email->save();
            }
        }
    }

    /**
     * Delete messages that are no longer present in the IMAP folder.
     *
     * @param User $user
     * @param string $folder
     * @param array $syncedUids
     * @return int
     */
    public function deleteMissing(User $user, string $folder, array $syncedUids): int
    {
        $deletedCount = Email::where('user_id', $user->id)
            ->where('folder', $folder)
            ->whereNotIn('imap_uid', $syncedUids)
            ->delete();
        
        if ($deletedCount > 0) {
            Log::info("Deleted {$deletedCount} messages from folder {$folder} for user {$user->id} that were missing on IMAP.");
        }

        return $deletedCount;
    }
}

<?php

namespace App\Jobs;

use App\Models\Email;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webklex\IMAP\Facades\Client;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ImapSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $password,
        public string $folder = 'INBOX'
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $client = Client::account('default');
            $client->username = $this->user->email;
            $client->password = $this->password;
            
            $client->connect();

            $folder = $client->getFolder($this->folder);
            
            if (!$folder) {
                Log::warning("Folder {$this->folder} not found for user {$this->user->id}");
                return;
            }

            $messages = $folder->query()->all()->get();
            $syncedUids = [];

            foreach ($messages as $message) {
                Log::debug("Syncing email from folder {$this->folder}: UID={$message->uid}, Subject={$message->subject}, Seen={$message->getFlags()->has('seen')}");
                
                $syncedUids[] = $message->uid;
                $body = $message->getHTMLBody() ?: $message->getTextBody();

                $email = Email::updateOrCreate(
                    [
                        'user_id' => $this->user->id,
                        'folder' => $this->folder,
                        'imap_uid' => $message->uid,
                    ],
                    [
                        'subject' => $message->subject,
                        'from' => $message->from->first()->mail ?? '',
                        'to' => $message->to->first()->mail ?? '',
                        'date' => $message->date,
                        'body' => $body,
                        'is_read' => $message->getFlags()->has('seen') ? 1 : 0,
                    ]
                );

                // Handle attachments
                $attachments = $message->getAttachments();
                foreach ($attachments as $attachment) {
                    $filename = $attachment->getName() ?: 'unnamed_attachment';
                    $contentType = $attachment->getContentType();
                    $size = $attachment->getSize();
                    $contentId = $attachment->getContentId();
                    // Strip brackets from contentId if present
                    $cleanContentId = str_replace(['<', '>'], '', (string)$contentId);
                    
                    $isInline = strtolower((string)$attachment->disposition) === 'inline';

                    $storagePath = "attachments/{$this->user->id}/{$email->id}";
                    
                    // Save to storage
                    Storage::disk('public')->put("{$storagePath}/{$filename}", $attachment->getContent());

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

            // Delete messages that are no longer present in the IMAP folder
            $deletedCount = Email::where('user_id', $this->user->id)
                ->where('folder', $this->folder)
                ->whereNotIn('imap_uid', $syncedUids)
                ->delete();
            
            if ($deletedCount > 0) {
                Log::info("Deleted {$deletedCount} messages from folder {$this->folder} for user {$this->user->id} that were missing on IMAP.");
            }

            // Mark sync as completed for this user and folder
            Cache::put("sync_completed_{$this->user->id}_{$this->folder}", true, 300); // 5 mins
        } catch (Exception $e) {
            Log::error("Failed to sync IMAP for user {$this->user->id}: " . $e->getMessage());
            throw $e;
        }
    }
}

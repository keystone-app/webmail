<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\ImapConnectionManager;
use App\Services\ImapMessageParser;
use App\Services\ImapMessageRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;
use Illuminate\Support\Facades\Log;
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
    public function handle(
        ImapConnectionManager $connectionManager,
        ImapMessageParser $parser,
        ImapMessageRepository $repository
    ): void {
        try {
            $client = $connectionManager->connect($this->user, $this->password);

            $folder = $client->getFolder($this->folder);
            
            if (!$folder) {
                Log::warning("Folder {$this->folder} not found for user {$this->user->id}");
                return;
            }

            $messages = $folder->query()->all()->get();
            $syncedUids = [];

            foreach ($messages as $message) {
                Log::debug("Syncing email from folder {$this->folder}: UID={$message->uid}, Subject={$message->subject}");
                
                $details = $parser->parseDetails($message);
                $body = $parser->parseBody($message);
                $attachments = $parser->extractAttachments($message);

                $email = $repository->upsertEmail($this->user, $this->folder, $details, $body);
                $repository->storeAttachments($email, $this->user, $attachments);

                $syncedUids[] = $message->uid;
            }

            // Mirror sync: Delete messages no longer present on IMAP
            $repository->deleteMissing($this->user, $this->folder, $syncedUids);

            // Mark sync as completed for this user and folder
            Cache::put("sync_completed_{$this->user->id}_{$this->folder}", true, 300); // 5 mins
        } catch (Exception $e) {
            Log::error("Failed to sync IMAP for user {$this->user->id}: " . $e->getMessage());
            throw $e;
        }
    }
}

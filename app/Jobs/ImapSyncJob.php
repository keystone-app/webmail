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

class ImapSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $password
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

            $folder = $client->getFolder('INBOX');
            $messages = $folder->query()->all()->get();

            foreach ($messages as $message) {
                Email::updateOrCreate(
                    [
                        'user_id' => $this->user->id,
                        'folder' => 'INBOX',
                        'imap_uid' => $message->uid,
                    ],
                    [
                        'subject' => $message->subject,
                        'from' => $message->from->first()->mail ?? '',
                        'to' => $message->to->first()->mail ?? '',
                        'date' => $message->date,
                        'body' => $message->getHTMLBody() ?: $message->getTextBody(),
                        'is_read' => $message->is_read ?? true,
                    ]
                );
            }
        } catch (Exception $e) {
            Log::error("Failed to sync IMAP for user {$this->user->id}: " . $e->getMessage());
            throw $e;
        }
    }
}

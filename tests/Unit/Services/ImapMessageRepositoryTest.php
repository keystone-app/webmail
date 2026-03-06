<?php

namespace Tests\Unit\Services;

use App\Models\Email;
use App\Models\User;
use App\Services\ImapMessageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImapMessageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_upsert_email(): void
    {
        $user = User::factory()->create();
        $details = [
            'imap_uid' => 100,
            'subject' => 'Test Subject',
            'from' => 'sender@test.com',
            'to' => 'user@test.com',
            'date' => now(),
            'is_read' => 1,
        ];
        $body = '<p>Test Body</p>';
        $folder = 'INBOX';

        $repository = new ImapMessageRepository();
        $email = $repository->upsertEmail($user, $folder, $details, $body);

        $this->assertDatabaseHas('emails', [
            'user_id' => $user->id,
            'imap_uid' => 100,
            'folder' => $folder,
            'subject' => 'Test Subject',
        ]);
        $this->assertEquals($body, $email->body);
    }

    public function test_it_can_store_attachments(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);
        
        $attachments = [
            [
                'filename' => 'test.txt',
                'content_type' => 'text/plain',
                'size' => 12,
                'content_id' => 'cid1',
                'content' => 'Hello World',
                'is_inline' => false,
            ]
        ];

        $repository = new ImapMessageRepository();
        $repository->storeAttachments($email, $user, $attachments);

        $this->assertDatabaseHas('attachments', [
            'email_id' => $email->id,
            'filename' => 'test.txt',
        ]);
        
        $attachment = $email->attachments()->first();
        Storage::disk('public')->assertExists($attachment->path);
    }

    public function test_it_can_delete_missing_messages(): void
    {
        $user = User::factory()->create();
        Email::factory()->create(['user_id' => $user->id, 'imap_uid' => 1, 'folder' => 'INBOX']);
        Email::factory()->create(['user_id' => $user->id, 'imap_uid' => 2, 'folder' => 'INBOX']);
        
        $syncedUids = [1];
        $folder = 'INBOX';

        $repository = new ImapMessageRepository();
        $repository->deleteMissing($user, $folder, $syncedUids);

        $this->assertDatabaseHas('emails', ['imap_uid' => 1]);
        $this->assertDatabaseMissing('emails', ['imap_uid' => 2]);
    }
}

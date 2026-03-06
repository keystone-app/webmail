<?php

namespace Tests\Feature;

use App\Models\Draft;
use App\Models\User;
use App\Mail\MailMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MessageSendApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_an_email_with_attachments(): void
    {
        Mail::fake();
        Storage::fake('local');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)->postJson('/api/messages/send', [
            'to' => 'recipient@example.com',
            'subject' => 'Test Subject',
            'body' => '<p>Test Body</p>',
            'attachments' => [$file],
        ]);

        $response->assertStatus(200);
        
        Mail::assertSent(MailMessage::class, function ($mail) {
            return $mail->hasTo('recipient@example.com') &&
                   count($mail->attachmentsData) === 1 &&
                   $mail->attachmentsData[0]['filename'] === 'document.pdf';
        });
    }

    public function test_authenticated_user_can_send_an_email(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $draft = Draft::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->postJson('/api/messages/send', [
            'draft_id' => $draft->id,
            'to' => 'recipient@example.com',
            'subject' => 'Test Subject',
            'body' => '<p>Test Body</p>',
        ]);

        $response->assertStatus(200);
        
        Mail::assertSent(MailMessage::class, function ($mail) {
            return $mail->hasTo('recipient@example.com') &&
                   $mail->subject === 'Test Subject';
        });

        // Draft should be deleted after successful send
        $this->assertDatabaseMissing('drafts', ['id' => $draft->id]);
    }

    public function test_unauthenticated_user_cannot_send_email(): void
    {
        $response = $this->postJson('/api/messages/send', [
            'to' => 'recipient@example.com',
            'subject' => 'Test Subject',
            'body' => '<p>Test Body</p>',
        ]);

        $response->assertStatus(401);
    }
}

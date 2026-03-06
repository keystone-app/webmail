<?php

namespace Tests\Unit;

use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_email_can_be_created(): void
    {
        $user = User::factory()->create();

        $email = Email::create([
            'user_id' => $user->id,
            'imap_uid' => 12345,
            'folder' => 'INBOX',
            'subject' => 'Test Subject',
            'from' => 'sender@example.com',
            'to' => 'recipient@example.com',
            'date' => now(),
            'body' => 'Test Body',
            'is_read' => false,
        ]);

        $this->assertDatabaseHas('emails', [
            'id' => $email->id,
            'imap_uid' => 12345,
        ]);
    }

    public function test_an_email_belongs_to_a_user(): void
    {
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $email->user);
        $this->assertEquals($user->id, $email->user->id);
    }
}

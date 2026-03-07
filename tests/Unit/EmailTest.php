<?php

namespace Tests\Unit;

use App\Models\Email;
use App\Models\MailAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_email_can_be_created(): void
    {
        $account = MailAccount::factory()->create();

        $email = Email::create([
            'account_id' => $account->id,
            'uid' => 12345,
            'folder' => 'INBOX',
            'subject' => 'Test Subject',
            'from_email' => 'sender@example.com',
            'sender_name' => 'Sender',
            'recipients' => 'recipient@example.com',
            'sent_at' => now(),
            'is_seen' => false,
        ]);

        $this->assertDatabaseHas('emails', [
            'id' => $email->id,
            'uid' => 12345,
        ]);
    }

    public function test_an_email_belongs_to_an_account(): void
    {
        $account = MailAccount::factory()->create();
        $email = Email::factory()->create(['account_id' => $account->id]);

        $this->assertInstanceOf(MailAccount::class, $email->account);
        $this->assertEquals($account->id, $email->account->id);
    }
}

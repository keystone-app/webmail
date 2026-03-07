<?php

namespace Tests\Unit;

use App\Models\MailAccount;
use App\Models\User;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_mail_account_with_uuid(): void
    {
        $user = User::factory()->create();
        $account = MailAccount::create([
            'user_id' => $user->id,
            'email' => 'test@example.com',
            'imap_uidvalidity' => 12345,
        ]);

        $this->assertNotNull($account->id);
        $this->assertIsString($account->id);
        $this->assertEquals(36, strlen($account->id)); // UUID length
        $this->assertEquals('test@example.com', $account->email);
    }

    public function test_it_belongs_to_a_user(): void
    {
        $user = User::factory()->create();
        $account = MailAccount::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $account->user);
        $this->assertEquals($user->id, $account->user->id);
    }

    public function test_it_has_many_emails(): void
    {
        $account = MailAccount::factory()->create();
        Email::factory()->count(3)->create(['account_id' => $account->id]);

        $this->assertCount(3, $account->emails);
        $this->assertInstanceOf(Email::class, $account->emails->first());
    }
}

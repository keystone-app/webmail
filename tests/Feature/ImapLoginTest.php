<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ImapAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class ImapLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_imap_login_creates_user_and_logs_them_in(): void
    {
        $email = 'user@example.com';
        $password = 'password';

        $imapService = Mockery::mock(ImapAuthService::class);
        $imapService->shouldReceive('authenticate')->with($email, $password)->andReturn(true);
        $this->app->instance(ImapAuthService::class, $imapService);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticated();
        
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($email, $user->name); // Default name to email for now
    }

    public function test_failed_imap_login_does_not_log_user_in(): void
    {
        $email = 'user@example.com';
        $password = 'wrong_password';

        $imapService = Mockery::mock(ImapAuthService::class);
        $imapService->shouldReceive('authenticate')->with($email, $password)->andReturn(false);
        $this->app->instance(ImapAuthService::class, $imapService);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertEquals(0, User::count());
    }
}

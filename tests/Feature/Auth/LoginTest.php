<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $password = 'password123';
        $encryptionKey = bin2hex(random_bytes(16));
        $user = User::factory()->create([
            'password' => Hash::make($password),
            'encryption_key' => $encryptionKey,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);

        // Verify password is encrypted in session
        $this->assertTrue(Session::has('imap_password'), 'imap_password should be in session.');
        
        // Decrypt and verify
        $service = new \App\Services\EncryptionService();
        $decrypted = $service->decrypt(Session::get('imap_password'), $encryptionKey);
        $this->assertEquals($password, $decrypted);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }
}

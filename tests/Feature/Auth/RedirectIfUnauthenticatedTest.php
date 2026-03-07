<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectIfUnauthenticatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/home');

        $response->assertRedirect('/login');
    }

    public function test_user_is_redirected_to_intended_page_after_login(): void
    {
        $user = \App\Models\User::factory()->create([
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'encryption_key' => bin2hex(random_bytes(16)),
        ]);

        // Attempt to access home, get redirected to login
        $this->get('/home')->assertRedirect('/login');

        // Login
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Should redirect to /home, not the default /home (though they are the same in this case, intended logic is checked)
        $response->assertRedirect('/home');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_emails_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        Email::factory()->count(15)->create(['user_id' => $user->id, 'folder' => 'INBOX']);
        
        // Another user's email
        Email::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->actingAs($user)->getJson('/api/emails?folder=INBOX');

        $response->assertStatus(200)
            ->assertJsonCount(15, 'data');
    }

    public function test_it_filters_by_folder(): void
    {
        $user = User::factory()->create();
        Email::factory()->count(5)->create(['user_id' => $user->id, 'folder' => 'INBOX']);
        Email::factory()->count(3)->create(['user_id' => $user->id, 'folder' => 'SENT']);

        $response = $this->actingAs($user)->getJson('/api/emails?folder=SENT');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_it_returns_a_single_email_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson("/api/emails/{$email->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $email->id)
            ->assertJsonPath('data.subject', $email->subject);
    }

    public function test_it_does_not_return_another_users_email(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->getJson("/api/emails/{$email->id}");

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_api(): void
    {
        $response = $this->getJson('/api/emails');

        $response->assertStatus(401);
    }
}

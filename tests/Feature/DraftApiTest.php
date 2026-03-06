<?php

namespace Tests\Feature;

use App\Models\Draft;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DraftApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_save_a_draft(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/drafts', [
            'to' => 'recipient@example.com',
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('drafts', [
            'user_id' => $user->id,
            'subject' => 'Test Subject',
        ]);
    }

    public function test_authenticated_user_can_update_a_draft(): void
    {
        $user = User::factory()->create();
        $draft = Draft::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson("/api/drafts/{$draft->id}", [
            'subject' => 'Updated Subject',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('drafts', [
            'id' => $draft->id,
            'subject' => 'Updated Subject',
        ]);
    }

    public function test_user_cannot_update_others_draft(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $draft = Draft::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->putJson("/api/drafts/{$draft->id}", [
            'subject' => 'Hacked Subject',
        ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_save_draft(): void
    {
        $response = $this->postJson('/api/drafts', [
            'subject' => 'Anonymous Draft',
        ]);

        $response->assertStatus(401);
    }
}

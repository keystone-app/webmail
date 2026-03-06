<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_their_attachment(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $user->id]);
        $attachment = Attachment::factory()->create([
            'email_id' => $email->id,
            'path' => 'attachments/test.jpg',
            'filename' => 'test.jpg'
        ]);

        Storage::disk('public')->put($attachment->path, 'content');

        $response = $this->actingAs($user)->get("/api/attachments/{$attachment->id}");

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_others_attachment(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $email = Email::factory()->create(['user_id' => $otherUser->id]);
        $attachment = Attachment::factory()->create([
            'email_id' => $email->id,
            'path' => 'attachments/test.jpg'
        ]);

        Storage::disk('public')->put($attachment->path, 'content');

        $response = $this->actingAs($user)->get("/api/attachments/{$attachment->id}");

        $response->assertStatus(403);
    }

    public function test_guest_cannot_view_attachment(): void
    {
        $attachment = Attachment::factory()->create();

        $response = $this->get("/api/attachments/{$attachment->id}");

        $response->assertRedirect('/login');
    }
}

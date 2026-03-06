<?php

namespace Tests\Unit;

use App\Models\Draft;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DraftTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_draft_can_be_created(): void
    {
        $user = User::factory()->create();

        $draft = Draft::create([
            'user_id' => $user->id,
            'to' => 'recipient@example.com',
            'cc' => 'cc@example.com',
            'bcc' => 'bcc@example.com',
            'subject' => 'Test Draft',
            'body' => '<p>Hello World</p>',
            'attachments_metadata' => ['file1.pdf', 'file2.jpg'],
        ]);

        $this->assertDatabaseHas('drafts', [
            'id' => $draft->id,
            'subject' => 'Test Draft',
        ]);
    }

    public function test_a_draft_belongs_to_a_user(): void
    {
        $user = User::factory()->create();
        $draft = Draft::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $draft->user);
        $this->assertEquals($user->id, $draft->user->id);
    }
}

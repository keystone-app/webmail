<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_table_has_encryption_key_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('users', 'encryption_key'),
            'The users table should have an encryption_key column.'
        );
    }

    public function test_user_model_has_encryption_key_in_fillable(): void
    {
        $user = new User();
        $this->assertContains('encryption_key', $user->getFillable(), 'encryption_key should be fillable on the User model.');
    }
}

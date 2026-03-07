<?php

namespace Database\Factories;

use App\Models\MailAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => MailAccount::factory(),
            'message_id' => $this->faker->uuid() . '@example.com',
            'uid' => $this->faker->unique()->numberBetween(1, 100000),
            'folder' => 'INBOX',
            'subject' => $this->faker->sentence(),
            'from_email' => $this->faker->safeEmail(),
            'sender_name' => $this->faker->name(),
            'recipients' => $this->faker->safeEmail(),
            'sent_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'is_seen' => $this->faker->boolean(),
            'has_attachments' => $this->faker->boolean(),
            'thread_id' => $this->faker->uuid(),
        ];
    }
}

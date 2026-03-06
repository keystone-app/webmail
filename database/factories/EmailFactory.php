<?php

namespace Database\Factories;

use App\Models\User;
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
            'user_id' => User::factory(),
            'imap_uid' => $this->faker->unique()->numberBetween(1, 100000),
            'folder' => 'INBOX',
            'subject' => $this->faker->sentence(),
            'from' => $this->faker->safeEmail(),
            'to' => $this->faker->safeEmail(),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'body' => $this->faker->paragraph(10),
            'is_read' => $this->faker->boolean(),
        ];
    }
}

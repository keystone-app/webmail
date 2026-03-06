<?php

namespace Database\Factories;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email_id' => Email::factory(),
            'filename' => $this->faker->word() . '.pdf',
            'content_type' => 'application/pdf',
            'size' => $this->faker->numberBetween(100, 5000),
            'path' => 'attachments/' . $this->faker->uuid() . '.pdf',
            'is_inline' => false,
            'content_id' => null,
        ];
    }
}

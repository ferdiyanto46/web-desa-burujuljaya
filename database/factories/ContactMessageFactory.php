<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
final class ContactMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<ContactMessage>
     */
    protected $model = ContactMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->boolean(70) ? fake()->numerify('+628##########') : null,
            'subject' => fake()->sentence(4),
            'message' => fake()->paragraphs(2, true),
            'is_read' => fake()->boolean(40),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

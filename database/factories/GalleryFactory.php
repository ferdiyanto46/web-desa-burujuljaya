<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'status' => PublicationStatus::Published->value,
            'published_at' => now(),
            'is_pinned' => fake()->boolean(20),
            'user_id' => User::factory(),
        ];
    }
}

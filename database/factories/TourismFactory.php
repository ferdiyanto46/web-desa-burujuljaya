<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tourism;
use App\Models\TourismCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tourism>
 */
final class TourismFactory extends Factory
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
            'category_id' => TourismCategory::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->paragraphs(2, true),
            'address' => fake()->address(),
            'cover_image' => null,
            'is_active' => fake()->boolean(90),
        ];
    }
}

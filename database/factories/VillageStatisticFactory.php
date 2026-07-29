<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\StatisticCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

final class VillageStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => StatisticCategory::factory(),
            'name' => fake()->word(),
            'value' => fake()->numberBetween(0, 1500),
            'year' => fake()->randomElement([2024, 2025, 2026]),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TourismCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TourismCategory>
 */
class TourismCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'description' => $this->faker->sentence(),
        ];
    }
}

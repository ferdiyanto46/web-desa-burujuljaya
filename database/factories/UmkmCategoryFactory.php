<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\UmkmCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<UmkmCategory>
 */
final class UmkmCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => str($name)->title()->toString(),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
        ];
    }
}

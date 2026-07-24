<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArticleCategory>
 */
class ArticleCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'slug' => null, // Auto-generated in boot
            'description' => fake()->optional()->sentence(),
        ];
    }
}

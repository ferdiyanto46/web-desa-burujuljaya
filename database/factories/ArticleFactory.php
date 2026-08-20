<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ulid' => null, // Auto-generated in boot
            'user_id' => User::factory(),
            'category_id' => ArticleCategory::factory(),
            'title' => fake()->sentence(),
            'slug' => null, // Auto-generated in boot
            'excerpt' => fake()->paragraph(),
            'content' => collect(fake()->paragraphs(5))->map(fn ($p) => "<p>$p</p>")->implode(''),
            'featured_image_path' => null,
            'status' => PublicationStatus::Draft,
            'is_featured' => false,
            'views' => 0,
            'published_at' => null,
            'meta_title' => null,
            'meta_description' => null,
            'og_image_path' => null,
        ];
    }

    /**
     * Indicate that the article is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PublicationStatus::Published,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'views' => fake()->numberBetween(10, 1000),
        ]);
    }

    /**
     * Indicate that the article is scheduled for the future.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PublicationStatus::Scheduled,
            'published_at' => fake()->dateTimeBetween('now', '+1 month'),
        ]);
    }

    /**
     * Indicate that the article is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PublicationStatus::Draft,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the article is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}

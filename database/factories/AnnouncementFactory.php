<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ArticleStatus;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
final class AnnouncementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Lazy load author via closure to avoid N+1 spawn if overridden
            'user_id' => fn () => User::factory()->create()->id,
            'title' => $this->faker->sentence(),
            'excerpt' => $this->faker->paragraph(2),
            'content' => '<p>'.implode('</p><p>', $this->faker->paragraphs(4)).'</p>',
            'status' => ArticleStatus::Draft,
            'is_pinned' => false,
            'end_date' => null,
            'published_at' => null,
        ];
    }

    /**
     * Indicate that the announcement is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Published,
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the announcement is scheduled.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Scheduled,
            'published_at' => $this->faker->dateTimeBetween('now', '+1 month'),
        ]);
    }

    /**
     * Indicate that the announcement is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ArticleStatus::Archived,
        ]);
    }

    /**
     * Indicate that the announcement is pinned.
     */
    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
        ]);
    }

    /**
     * Indicate that the announcement will expire in the future.
     */
    public function withEndDate(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
        ]);
    }

    /**
     * Indicate that the announcement has already expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_date' => $this->faker->dateTimeBetween('-1 month', 'yesterday'),
        ]);
    }
}

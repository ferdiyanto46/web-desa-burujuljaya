<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $question = rtrim($this->faker->sentence(), '.').'?';

        return [
            'question' => $question,
            'slug' => Str::slug($question).'-'.Str::random(5),
            'answer' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement(['Umum', 'Layanan', 'Administrasi', 'Fasilitas', 'Keuangan']),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_featured' => $this->faker->boolean(20),
            'status' => PublicationStatus::Published->value,
            'published_at' => now()->subDays($this->faker->numberBetween(1, 30)),
            'view_count' => $this->faker->numberBetween(0, 500),
            'meta_title' => $this->faker->text(60),
            'meta_description' => $this->faker->text(150),
            'canonical_url' => null,
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the faq is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PublicationStatus::Published->value,
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the faq is in draft status.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PublicationStatus::Draft->value,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the faq is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PublicationStatus::Archived->value,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the faq is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}

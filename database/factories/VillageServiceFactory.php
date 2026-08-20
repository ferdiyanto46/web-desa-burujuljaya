<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\User;
use App\Models\VillageService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageService>
 */
class VillageServiceFactory extends Factory
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
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(),
            'excerpt' => $this->faker->paragraph(),
            'description' => $this->faker->paragraphs(3, true),
            'requirements' => $this->faker->paragraphs(2, true),
            'procedures' => $this->faker->paragraphs(2, true),
            'processing_time' => '1-2 Hari Kerja',
            'cost' => 'Gratis',
            'service_hours' => 'Senin-Jumat 08:00-15:00',
            'status' => PublicationStatus::Draft,
            'is_pinned' => false,
            'sort_order' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);
    }
}

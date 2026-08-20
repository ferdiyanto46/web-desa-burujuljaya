<?php

namespace Database\Factories;

use App\Models\HomepageSection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<HomepageSection>
 */
class HomepageSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ulid' => (string) Str::ulid(),
            'section_key' => $this->faker->unique()->word(),
            'title' => $this->faker->sentence(),
            'subtitle' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'configuration' => [],
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_enabled' => true,
            'status' => 'published',
            'published_at' => now(),
            'user_id' => 1,
        ];
    }
}

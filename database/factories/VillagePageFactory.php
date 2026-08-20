<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Enums\VillagePageType;
/**
 * @extends Factory<VillagePage>
 */
use App\Models\User;
use App\Models\VillagePage;
use Illuminate\Database\Eloquent\Factories\Factory;

class VillagePageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_column(VillagePageType::cases(), 'value');
        $title = $this->faker->sentence(4);

        return [
            'page_type' => $this->faker->randomElement($types),
            'title' => $title,
            'excerpt' => $this->faker->paragraph(),
            'content' => '<p>'.implode('</p><p>', $this->faker->paragraphs(3)).'</p>',
            'featured_image_path' => null,
            'status' => PublicationStatus::Published->value,
            'published_at' => now(),
            'is_pinned' => $this->faker->boolean(20),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'meta_title' => $title,
            'meta_description' => $this->faker->text(160),
            'canonical_url' => null,
            'user_id' => User::factory(),
        ];
    }
}

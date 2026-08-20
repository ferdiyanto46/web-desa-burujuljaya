<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\Tourism;
use App\Models\TourismCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tourism>
 */
class TourismFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);
        $status = $this->faker->randomElement(PublicationStatus::cases());
        $lat = $this->faker->latitude(-7.0, -6.0); // West Java area roughly
        $lng = $this->faker->longitude(107.0, 108.0);

        return [
            'category_id' => TourismCategory::factory(),
            'user_id' => User::factory(),
            'title' => $title,
            'excerpt' => $this->faker->text(200),
            'description' => $this->faker->paragraphs(3, true),
            'featured_image_path' => null,
            'address' => $this->faker->address(),
            'latitude' => (string) $lat,
            'longitude' => (string) $lng,
            'google_maps_url' => sprintf('https://maps.google.com/?q=%s,%s', $lat, $lng),
            'opening_hours' => '08:00 - 17:00',
            'ticket_price' => 'Rp '.$this->faker->numberBetween(5, 50).'0.000',
            'contact_person' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'status' => $status,
            'published_at' => $status === PublicationStatus::Published ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
            'is_featured' => $this->faker->boolean(20),
            'featured_order' => 0,
            'is_pinned' => $this->faker->boolean(10),
            'view_count' => $this->faker->numberBetween(0, 1000),
            'meta_title' => $title,
            'meta_description' => $this->faker->sentence(),
        ];
    }
}

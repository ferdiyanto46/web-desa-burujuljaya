<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\Umkm;
use App\Models\UmkmCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Umkm>
 */
final class UmkmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->company();

        return [
            'user_id' => User::factory(),
            'category_id' => UmkmCategory::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'owner_name' => fake()->name(),
            'description' => fake()->paragraphs(2, true),
            'featured_image_path' => null,
            'address' => fake()->address(),
            'latitude' => fake()->latitude(-8.0, -6.0), // Around West Java
            'longitude' => fake()->longitude(106.0, 109.0),
            'google_maps_url' => null, // Boot event handles this
            'phone' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'website' => fake()->url(),
            'instagram' => fake()->userName(),
            'facebook' => fake()->userName(),
            'operating_hours' => '08:00 - 17:00',
            'status' => fake()->randomElement(PublicationStatus::cases()),
            'published_at' => fake()->optional(0.8)->dateTimeThisYear(),
            'is_featured' => fake()->boolean(20),
            'is_pinned' => fake()->boolean(10),
            'view_count' => fake()->numberBetween(0, 1000),
            'featured_order' => fake()->numberBetween(0, 10),
        ];
    }
}

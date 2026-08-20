<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryPhoto>
 */
class GalleryPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gallery_id' => Gallery::factory(),
            'image_path' => 'galleries/fake-'.fake()->uuid().'.jpg',
            'caption' => fake()->sentence(),
            'alt_text' => fake()->words(3, true),
            'photographer' => fake()->name(),
            'file_size' => fake()->numberBetween(100000, 5000000),
            'width' => 1920,
            'height' => 1080,
            'mime' => 'image/jpeg',
            'extension' => 'jpg',
            'disk' => 'public',
            'sort_order' => 0,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryPhoto>
 */
final class GalleryPhotoFactory extends Factory
{
    protected $model = GalleryPhoto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'album_id' => GalleryAlbum::factory(),
            'title' => fake()->sentence(4),
            'photo_path' => 'gallery/'.fake()->uuid().'.jpg',
            'size' => fake()->numberBetween(100000, 5000000), // 100KB to 5MB
            'order' => fake()->numberBetween(0, 100),
        ];
    }
}

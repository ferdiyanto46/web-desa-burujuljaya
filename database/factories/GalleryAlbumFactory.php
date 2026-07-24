<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\GalleryAlbum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryAlbum>
 */
final class GalleryAlbumFactory extends Factory
{
    protected $model = GalleryAlbum::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'cover_photo' => 'gallery/'.fake()->uuid().'.jpg',
            'is_active' => fake()->boolean(90),
        ];
    }
}

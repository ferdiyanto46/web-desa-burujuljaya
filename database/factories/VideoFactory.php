<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
final class VideoFactory extends Factory
{
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(5),
            'video_url' => 'https://www.youtube.com/watch?v='.fake()->lexify('???????????'),
            'thumbnail_url' => fake()->imageUrl(),
            'is_active' => fake()->boolean(90),
        ];
    }
}

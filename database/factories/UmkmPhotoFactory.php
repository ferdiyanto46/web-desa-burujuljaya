<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Umkm;
use App\Models\UmkmPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UmkmPhoto>
 */
class UmkmPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'umkm_id' => Umkm::factory(),
            'file_path' => fake()->imageUrl(),
            'caption' => fake()->optional(0.7)->sentence(),
            'order_column' => fake()->numberBetween(0, 10),
        ];
    }
}

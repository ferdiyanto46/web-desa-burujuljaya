<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Symfony\Component\Uid\Ulid;

/**
 * @extends Factory<ProductPhoto>
 */
class ProductPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ulid' => (string) Ulid::generate(),
            'product_id' => Product::factory(),
            'image_path' => null,
            'caption' => fake()->sentence(),
            'alt_text' => fake()->words(3, true),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}

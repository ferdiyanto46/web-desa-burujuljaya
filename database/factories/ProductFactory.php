<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => ProductCategory::factory(),
            'umkm_id' => fake()->boolean(70) ? Umkm::factory() : null,
            'name' => fake()->words(3, true),
            'description' => fake()->paragraphs(2, true),
            'price' => fake()->numberBetween(10, 500) * 1000,
            'unit' => fake()->randomElement(['Pcs', 'Kg', 'Pack', 'Gram']),
            'cover_image' => null,
            'is_active' => fake()->boolean(90),
        ];
    }
}

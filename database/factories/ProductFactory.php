<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'ulid' => (string) Ulid::generate(),
            'user_id' => User::factory(),
            'category_id' => ProductCategory::factory(),
            'umkm_id' => Umkm::factory(),
            'title' => str($title)->title()->toString(),
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'featured_image_path' => null,
            'sku' => fake()->unique()->numerify('SKU-####'),
            'price' => fake()->numberBetween(10000, 500000),
            'stock' => fake()->numberBetween(0, 100),
            'unit' => fake()->randomElement(['pcs', 'kg', 'box']),
            'weight' => fake()->randomFloat(2, 0.1, 5.0),
            'is_featured' => fake()->boolean(20),
            'is_pinned' => fake()->boolean(10),
            'status' => PublicationStatus::Published,
            'published_at' => now(),
            'view_count' => fake()->numberBetween(0, 1000),
            'meta_title' => str($title)->title()->toString(),
            'meta_description' => fake()->sentence(),
        ];
    }
}

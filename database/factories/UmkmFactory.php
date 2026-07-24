<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Umkm;
use App\Models\UmkmCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'user_id' => User::factory(),
            'category_id' => UmkmCategory::factory(),
            'name' => fake()->company(),
            'owner_name' => fake()->name(),
            'description' => fake()->paragraphs(2, true),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'cover_image' => null,
            'is_active' => fake()->boolean(90),
        ];
    }
}

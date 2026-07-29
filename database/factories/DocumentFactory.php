<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence();

        return [
            'user_id' => User::factory(),
            'category_id' => DocumentCategory::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->boolean(80) ? fake()->paragraph() : null,
            'file_path' => 'documents/'.Str::random(40).'.pdf',
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(1024, 5000000), // 1KB to 5MB
            'download_count' => fake()->numberBetween(0, 1000),
            'is_public' => fake()->boolean(90),
        ];
    }
}

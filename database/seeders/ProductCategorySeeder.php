<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Makanan & Minuman',
            'Kerajinan',
            'Pakaian',
            'Hasil Bumi',
        ];

        foreach ($categories as $category) {
            ProductCategory::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }
    }
}

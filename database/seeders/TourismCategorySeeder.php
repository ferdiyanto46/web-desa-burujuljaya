<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TourismCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class TourismCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Wisata Alam',
            'Wisata Budaya',
            'Wisata Religi',
            'Wisata Buatan',
        ];

        foreach ($categories as $category) {
            TourismCategory::firstOrCreate(
                ['slug' => Str::slug($category)],
                ['name' => $category]
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\UmkmCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class UmkmCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Kuliner',
            'Kerajinan Tangan',
            'Pakaian',
            'Jasa',
            'Agribisnis',
        ];

        foreach ($categories as $category) {
            UmkmCategory::firstOrCreate(
                ['slug' => Str::slug($category)],
                ['name' => $category]
            );
        }
    }
}

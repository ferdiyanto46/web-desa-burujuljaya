<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StatisticCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class StatisticCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Pendidikan',
            'Pekerjaan',
            'Agama',
            'Kelompok Usia',
            'Golongan Darah',
            'Status Perkawinan',
        ];

        foreach ($categories as $category) {
            StatisticCategory::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'is_active' => true,
            ]);
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Pembangunan Desa',
            'Pemberdayaan Masyarakat',
            'Kesehatan',
            'Pendidikan',
            'BUMDes & Ekonomi',
            'Seni & Budaya',
            'Umum',
        ];

        foreach ($categories as $category) {
            ArticleCategory::firstOrCreate(
                ['name' => $category],
                ['description' => "Kategori berita seputar {$category} di Desa Burujuljaya."]
            );
        }
    }
}

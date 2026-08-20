<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Infrastruktur',
            'Pelayanan Publik',
            'Keamanan & Ketertiban',
            'Kesehatan',
            'Pendidikan',
            'Bantuan Sosial',
            'Lainnya',
        ];

        foreach ($categories as $category) {
            $slug = Str::slug($category);
            ComplaintCategory::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $category,
                    'is_active' => true,
                ]
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

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
            ComplaintCategory::factory()->create([
                'name' => $category,
                'slug' => strtolower(str_replace(' & ', '-', str_replace(' ', '-', $category))),
            ]);
        }
    }
}

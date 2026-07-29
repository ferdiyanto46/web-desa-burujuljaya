<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Peraturan Desa',
            'Keputusan Kepala Desa',
            'Laporan APBDes',
            'SOP Pelayanan',
            'Formulir Warga',
            'Dokumen RKPDes',
        ];

        foreach ($categories as $category) {
            DocumentCategory::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'is_active' => true,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['key' => 'hero_slider', 'title' => 'Slider Utama', 'subtitle' => 'Selamat datang di Desa', 'is_enabled' => true],
            ['key' => 'welcome_message', 'title' => 'Sambutan Kepala Desa', 'subtitle' => 'Selamat datang di website resmi', 'is_enabled' => true],
            ['key' => 'village_services', 'title' => 'Layanan Desa', 'subtitle' => 'Layanan administrasi untuk warga', 'is_enabled' => true],
            ['key' => 'featured_articles', 'title' => 'Berita Terkini', 'subtitle' => 'Informasi dan kabar terbaru dari desa', 'is_enabled' => true],
            ['key' => 'announcements', 'title' => 'Pengumuman', 'subtitle' => 'Pengumuman penting untuk warga', 'is_enabled' => true],
            ['key' => 'tourism', 'title' => 'Potensi Wisata', 'subtitle' => 'Jelajahi keindahan desa kami', 'is_enabled' => true],
            ['key' => 'umkm', 'title' => 'Produk UMKM', 'subtitle' => 'Dukung usaha kecil dan menengah lokal', 'is_enabled' => true],
            ['key' => 'village_products', 'title' => 'Produk Desa', 'subtitle' => 'Produk unggulan dari desa kami', 'is_enabled' => true],
            ['key' => 'gallery', 'title' => 'Galeri', 'subtitle' => 'Dokumentasi kegiatan desa', 'is_enabled' => true],
            ['key' => 'faq', 'title' => 'Tanya Jawab', 'subtitle' => 'Pertanyaan yang sering diajukan', 'is_enabled' => true],
            ['key' => 'statistics', 'title' => 'Statistik Desa', 'subtitle' => 'Data kependudukan', 'is_enabled' => true],
            ['key' => 'contact_preview', 'title' => 'Hubungi Kami', 'subtitle' => 'Informasi kontak dan layanan', 'is_enabled' => true],
            ['key' => 'map', 'title' => 'Peta Desa', 'subtitle' => 'Lokasi desa kami', 'is_enabled' => true],
        ];

        $user = User::first();

        foreach ($sections as $index => $section) {
            HomepageSection::firstOrCreate(
                ['section_key' => $section['key']],
                [
                    'ulid' => (string) Str::ulid(),
                    'title' => $section['title'],
                    'subtitle' => $section['subtitle'],
                    'sort_order' => $index,
                    'is_enabled' => $section['is_enabled'],
                    'configuration' => [],
                    'status' => 'published',
                    'published_at' => now(),
                    'user_id' => $user?->id,
                ]
            );
        }
    }
}

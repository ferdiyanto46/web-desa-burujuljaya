<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Seeds all application settings with their default values.
 *
 * Groups seeded:
 *   general     → site name, tagline, contact info, office hours
 *   seo         → meta title, description, keywords, OG image, analytics IDs
 *   social      → Facebook, Instagram, Twitter/X, YouTube, TikTok
 *   appearance  → colors, visitor counter, footer text
 *   email       → SMTP configuration (private; password is marked is_encrypted)
 *   maintenance → maintenance mode toggle and message
 *
 * Idempotent: uses firstOrCreate keyed on `key`.
 * Running this seeder multiple times will NOT duplicate or overwrite existing values.
 * This allows admins to change settings via the UI without fear of the seeder
 * resetting their changes on future re-deploys.
 *
 * Security notes:
 *   - Settings with is_encrypted = true (e.g., email.password) have their values
 *     seeded as empty strings. When the admin saves the value via the admin panel,
 *     SettingService will call Crypt::encryptString() before persisting.
 *   - Settings with is_public = false will never be returned to the frontend
 *     by SettingService::forPublic().
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('  Seeding application settings...');

        $created = 0;
        $skipped = 0;

        foreach ($this->definitions() as $definition) {
            $exists = Setting::where('key', $definition['key'])->exists();

            if (! $exists) {
                Setting::create($definition);
                $created++;
            } else {
                $skipped++;
            }
        }

        $this->command->info("  Settings: {$created} created, {$skipped} already exist.");
    }

    /**
     * Complete list of all application setting definitions.
     *
     * Ordered within each group by sort_order (ascending).
     * sort_order uses gaps of 10 within each group for easy future insertion.
     *
     * @return array<int, array<string, mixed>>
     */
    private function definitions(): array
    {
        return [
            // ─────────────────────────────────────────────────────────────────
            // GROUP: general
            // ─────────────────────────────────────────────────────────────────

            [
                'key' => 'general.site_name',
                'value' => 'Website Resmi Desa Burujuljaya',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nama Website',
                'description' => 'Nama website yang tampil di browser tab dan header halaman.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 10,
            ],
            [
                'key' => 'general.tagline',
                'value' => 'Transparansi, Partisipasi, Kemajuan Desa',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Tagline Website',
                'description' => 'Slogan singkat yang menggambarkan visi website desa.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 20,
            ],
            [
                'key' => 'general.site_description',
                'value' => 'Portal resmi Desa Burujuljaya untuk informasi, pelayanan, dan transparansi pemerintahan desa.',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Deskripsi Website',
                'description' => 'Deskripsi singkat yang menjelaskan tujuan website desa.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 30,
            ],
            [
                'key' => 'general.contact_email',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Email Kontak Publik',
                'description' => 'Alamat email yang ditampilkan pada halaman kontak dan footer.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 40,
            ],
            [
                'key' => 'general.contact_phone',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nomor Telepon Kantor',
                'description' => 'Nomor telepon kantor desa yang ditampilkan secara publik.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 50,
            ],
            [
                'key' => 'general.contact_whatsapp',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nomor WhatsApp',
                'description' => 'Nomor WhatsApp untuk tombol chat cepat. Format: 628xxxxxxxxx (tanpa tanda + atau spasi).',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 60,
            ],
            [
                'key' => 'general.office_hours',
                'value' => 'Senin – Jumat: 08.00 – 16.00 WIB',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Jam Operasional Kantor',
                'description' => 'Informasi jam pelayanan kantor desa. Ditampilkan di halaman kontak.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 70,
            ],

            // ─────────────────────────────────────────────────────────────────
            // GROUP: seo
            // ─────────────────────────────────────────────────────────────────

            [
                'key' => 'seo.meta_title',
                'value' => 'Website Resmi Desa Burujuljaya',
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Meta Title Default',
                'description' => 'Judul SEO default untuk halaman tanpa title khusus. Rekomendasi: maks 60 karakter.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 10,
            ],
            [
                'key' => 'seo.meta_description',
                'value' => 'Portal resmi Desa Burujuljaya. Temukan informasi desa, layanan administrasi, berita terkini, dan transparansi keuangan desa.',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Meta Description Default',
                'description' => 'Deskripsi SEO default untuk halaman tanpa deskripsi khusus. Rekomendasi: 120–160 karakter.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 20,
            ],
            [
                'key' => 'seo.meta_keywords',
                'value' => 'desa burujuljaya, website desa, layanan administrasi desa, berita desa tasikmalaya',
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Meta Keywords',
                'description' => 'Kata kunci SEO, dipisah koma. Pengaruh terhadap Google sangat terbatas; utamakan konten berkualitas.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 30,
            ],
            [
                'key' => 'seo.og_image_path',
                'value' => null,
                'type' => 'string',
                'group' => 'seo',
                'label' => 'OG Image (Open Graph)',
                'description' => 'Path gambar untuk pratinjau link di media sosial. Ukuran ideal: 1200×630 piksel.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 40,
            ],
            [
                'key' => 'seo.google_analytics_id',
                'value' => null,
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Google Analytics Measurement ID',
                'description' => 'ID pengukuran Google Analytics 4 (format: G-XXXXXXXXXX). Tidak ditampilkan ke publik.',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 50,
            ],
            [
                'key' => 'seo.google_tag_manager_id',
                'value' => null,
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Google Tag Manager ID',
                'description' => 'Container ID Google Tag Manager (format: GTM-XXXXXXX). Tidak ditampilkan ke publik.',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 60,
            ],

            // ─────────────────────────────────────────────────────────────────
            // GROUP: social
            // ─────────────────────────────────────────────────────────────────

            [
                'key' => 'social.facebook_url',
                'value' => null,
                'type' => 'string',
                'group' => 'social',
                'label' => 'URL Facebook',
                'description' => 'URL halaman Facebook resmi desa. Kosongkan jika tidak ada.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 10,
            ],
            [
                'key' => 'social.instagram_url',
                'value' => null,
                'type' => 'string',
                'group' => 'social',
                'label' => 'URL Instagram',
                'description' => 'URL profil Instagram resmi desa. Kosongkan jika tidak ada.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 20,
            ],
            [
                'key' => 'social.twitter_url',
                'value' => null,
                'type' => 'string',
                'group' => 'social',
                'label' => 'URL Twitter / X',
                'description' => 'URL profil Twitter/X resmi desa. Kosongkan jika tidak ada.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 30,
            ],
            [
                'key' => 'social.youtube_url',
                'value' => null,
                'type' => 'string',
                'group' => 'social',
                'label' => 'URL YouTube',
                'description' => 'URL channel YouTube resmi desa. Kosongkan jika tidak ada.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 40,
            ],
            [
                'key' => 'social.tiktok_url',
                'value' => null,
                'type' => 'string',
                'group' => 'social',
                'label' => 'URL TikTok',
                'description' => 'URL akun TikTok resmi desa. Kosongkan jika tidak ada.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 50,
            ],

            // ─────────────────────────────────────────────────────────────────
            // GROUP: appearance
            // ─────────────────────────────────────────────────────────────────

            [
                'key' => 'appearance.primary_color',
                'value' => '#16a34a',
                'type' => 'string',
                'group' => 'appearance',
                'label' => 'Warna Utama (Primary)',
                'description' => 'Warna utama tema website dalam format hex (#RRGGBB). Default: hijau pemerintahan.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 10,
            ],
            [
                'key' => 'appearance.secondary_color',
                'value' => '#2563eb',
                'type' => 'string',
                'group' => 'appearance',
                'label' => 'Warna Sekunder',
                'description' => 'Warna aksen/sekunder tema dalam format hex (#RRGGBB).',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 20,
            ],
            [
                'key' => 'appearance.show_visitor_counter',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'appearance',
                'label' => 'Tampilkan Penghitung Pengunjung',
                'description' => 'Menampilkan jumlah total pengunjung website di footer.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 30,
            ],
            [
                'key' => 'appearance.footer_text',
                'value' => '© '.date('Y').' Pemerintah Desa Burujuljaya. Hak Cipta Dilindungi.',
                'type' => 'text',
                'group' => 'appearance',
                'label' => 'Teks Footer',
                'description' => 'Teks hak cipta yang ditampilkan di bagian bawah setiap halaman.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 40,
            ],

            // ─────────────────────────────────────────────────────────────────
            // GROUP: email (SMTP — semua is_public = false)
            // ─────────────────────────────────────────────────────────────────

            [
                'key' => 'email.driver',
                'value' => 'smtp',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Driver Email',
                'description' => 'Driver pengiriman email.',
                'options' => [
                    ['value' => 'smtp', 'label' => 'SMTP'],
                    ['value' => 'sendmail', 'label' => 'Sendmail'],
                    ['value' => 'log', 'label' => 'Log (Development Only)'],
                ],
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 10,
            ],
            [
                'key' => 'email.host',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'SMTP Host',
                'description' => 'Server host SMTP (contoh: smtp.gmail.com, mail.your-domain.com).',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 20,
            ],
            [
                'key' => 'email.port',
                'value' => '587',
                'type' => 'integer',
                'group' => 'email',
                'label' => 'SMTP Port',
                'description' => 'Port SMTP: 587 (TLS/STARTTLS, direkomendasikan), 465 (SSL), 25 (tanpa enkripsi).',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 30,
            ],
            [
                'key' => 'email.encryption',
                'value' => 'tls',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Enkripsi SMTP',
                'description' => 'Jenis enkripsi koneksi SMTP.',
                'options' => [
                    ['value' => 'tls', 'label' => 'TLS / STARTTLS (Direkomendasikan)'],
                    ['value' => 'ssl', 'label' => 'SSL'],
                    ['value' => '', 'label' => 'Tanpa Enkripsi (Tidak Direkomendasikan)'],
                ],
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 40,
            ],
            [
                'key' => 'email.username',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'SMTP Username',
                'description' => 'Username untuk autentikasi SMTP (biasanya sama dengan alamat email pengirim).',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 50,
            ],
            [
                'key' => 'email.password',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'SMTP Password',
                'description' => 'Kata sandi SMTP. DISIMPAN TERENKRIPSI menggunakan Laravel Crypt. Kosongkan untuk reset.',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => true, // ← SettingService will Crypt::encryptString() on save
                'sort_order' => 60,
            ],
            [
                'key' => 'email.from_address',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Alamat Email Pengirim',
                'description' => 'Alamat email yang tertera sebagai pengirim (From) pada setiap email yang dikirim sistem.',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 70,
            ],
            [
                'key' => 'email.from_name',
                'value' => 'Desa Burujuljaya',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Nama Pengirim Email',
                'description' => 'Nama yang tertera sebagai pengirim pada setiap email yang dikirim sistem.',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 80,
            ],

            // ─────────────────────────────────────────────────────────────────
            // GROUP: maintenance
            // ─────────────────────────────────────────────────────────────────

            [
                'key' => 'maintenance.is_active',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'maintenance',
                'label' => 'Mode Pemeliharaan',
                'description' => 'Mengaktifkan halaman maintenance untuk pengunjung umum. Admin (super-admin, admin-desa) tetap dapat mengakses semua halaman.',
                'options' => null,
                'is_public' => false,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 10,
            ],
            [
                'key' => 'maintenance.message',
                'value' => 'Website sedang dalam pemeliharaan. Silakan kembali beberapa saat lagi. Terima kasih atas pengertian Anda.',
                'type' => 'text',
                'group' => 'maintenance',
                'label' => 'Pesan Pemeliharaan',
                'description' => 'Pesan yang ditampilkan kepada pengunjung saat mode pemeliharaan aktif.',
                'options' => null,
                'is_public' => true,
                'is_editable' => true,
                'is_encrypted' => false,
                'sort_order' => 20,
            ],
        ];
    }
}

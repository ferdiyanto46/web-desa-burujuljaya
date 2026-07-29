<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Seeders are organized by module and must run in dependency order.
     * Production seeders always run. Test fixtures are restricted to
     * non-production environments to prevent contaminating production data.
     */
    public function run(): void
    {
        // ── M1: Users & RBAC ─────────────────────────────────────────────────
        // RolePermissionSeeder must run before UserSeeder (role assignment dependency)
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);

        // ── M2: Foundation ────────────────────────────────────────────────────
        $this->call([
            VillageProfileSeeder::class,
            SettingSeeder::class,
        ]);

        // ── M3: Articles (News) ────────────────────────────────────────────────
        $this->call([
            // M3 (News Management)
            ArticleCategorySeeder::class,
            ArticleSeeder::class,

            // M4 (Announcements & Agenda)
            AnnouncementSeeder::class,
            AgendaSeeder::class,

            // M5 (Apparatus)
            ApparatusPositionSeeder::class,
            ApparatusSeeder::class,

            // M6 (Gallery & Albums)
            GalleryAlbumSeeder::class,
            GalleryPhotoSeeder::class,
            VideoSeeder::class,

            // M7 (Potentials)
            UmkmCategorySeeder::class,
            UmkmSeeder::class,
            TourismCategorySeeder::class,
            TourismSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,

            // M8 (Statistics)
            StatisticCategorySeeder::class,
            VillageStatisticSeeder::class,

            // M9 (Documents)
            DocumentCategorySeeder::class,
            DocumentSeeder::class,
            ComplaintCategorySeeder::class,
            ComplaintSeeder::class,
            ComplaintAttachmentSeeder::class,
            ComplaintHistorySeeder::class,

            // M11 (Audit)
            ActivityLogSeeder::class,

            // M12 (Website Mgmt)
            BannerSeeder::class,
            FaqSeeder::class,
            ContactMessageSeeder::class,
        ]);

        // ── Development / testing fixtures ────────────────────────────────────
        // Never executed in production — prevents test data from contaminating live data.
        if (! app()->isProduction()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}

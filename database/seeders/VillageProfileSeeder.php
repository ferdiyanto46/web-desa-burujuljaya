<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\VillageMission;
use App\Models\VillageProfile;
use Illuminate\Database\Seeder;

/**
 * Seeds the initial village profile and mission statements for Desa Burujuljaya.
 *
 * Idempotent:
 *   - Profile is created via firstOrCreate(id=1) — safe to re-run.
 *   - Missions are only seeded if none exist for the profile (doesntExist check).
 *
 * These are default/placeholder values.
 * The actual content MUST be updated by the village admin via the admin panel
 * after the first deployment:
 *   - village_code (BPS code)
 *   - phone, email, website_url
 *   - history (sejarah desa)
 *   - vision (visi desa yang ditetapkan dalam RPJMDes)
 *   - area_hectares, latitude, longitude
 *   - established_year
 *   - logo_path (uploaded via admin panel)
 *   - Mission statements (ganti dengan misi dari RPJMDes resmi)
 *
 * Mission sort_order uses gaps (10, 20, 30, …) so new missions can be inserted
 * between existing ones without renumbering all rows.
 */
class VillageProfileSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('  Creating village profile for Desa Burujuljaya...');

        /** @var VillageProfile $profile */
        $profile = VillageProfile::firstOrCreate(
            ['id' => 1],
            [
                'village_name' => 'Desa Burujuljaya',
                'village_code' => null,
                'district' => 'Manonjaya',
                'regency' => 'Kabupaten Tasikmalaya',
                'province' => 'Jawa Barat',
                'postal_code' => null,
                'address' => 'Kantor Desa Burujuljaya, Kecamatan Manonjaya, Kabupaten Tasikmalaya',
                'phone' => null,
                'email' => null,
                'website_url' => null,
                'history' => null,
                'vision' => 'Terwujudnya Desa Burujuljaya yang Maju, Mandiri, Sejahtera, dan Berkeadilan.',
                'area_hectares' => null,
                'latitude' => null,
                'longitude' => null,
                'established_year' => null,
                'logo_path' => null,
            ]
        );

        // Only seed missions if none exist — prevents duplication on re-run
        if ($profile->missions()->doesntExist()) {
            $this->seedDefaultMissions($profile);
            $this->command->info('  Default mission statements seeded.');
        } else {
            $this->command->line('  Missions already exist — skipped.');
        }

        $this->command->info("  Village profile ready: {$profile->village_name}");
    }

    /**
     * Insert generic placeholder missions based on common Indonesian village governance goals.
     *
     * IMPORTANT: These are placeholder missions. The village admin MUST replace
     * these with the official misi from the RPJMDes (Rencana Pembangunan Jangka
     * Menengah Desa) document via the admin panel.
     */
    private function seedDefaultMissions(VillageProfile $profile): void
    {
        $missions = [
            'Meningkatkan kualitas pelayanan administrasi publik yang cepat, mudah, transparan, dan akuntabel.',
            'Membangun dan meningkatkan infrastruktur desa yang memadai demi kesejahteraan seluruh warga.',
            'Meningkatkan pemberdayaan ekonomi masyarakat melalui pengembangan UMKM dan potensi lokal desa.',
            'Meningkatkan kualitas sumber daya manusia melalui pendidikan, pelatihan, dan pembinaan pemuda.',
            'Menjaga keamanan, ketertiban, dan kelestarian lingkungan hidup desa secara berkesinambungan.',
            'Meningkatkan partisipasi aktif masyarakat dalam proses perencanaan dan pembangunan desa.',
        ];

        foreach ($missions as $index => $content) {
            VillageMission::create([
                'village_profile_id' => $profile->id,
                'content' => $content,
                // Gaps of 10 allow easy insertion without renumbering: 10, 20, 30, ...
                'sort_order' => ($index + 1) * 10,
            ]);
        }
    }
}

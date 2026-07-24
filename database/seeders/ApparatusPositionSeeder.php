<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApparatusPosition;
use Illuminate\Database\Seeder;

final class ApparatusPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'Kepala Desa', 'order' => 1],
            ['name' => 'Sekretaris Desa', 'order' => 2],
            ['name' => 'Kepala Urusan Tata Usaha dan Umum', 'order' => 3],
            ['name' => 'Kepala Urusan Keuangan', 'order' => 4],
            ['name' => 'Kepala Urusan Perencanaan', 'order' => 5],
            ['name' => 'Kepala Seksi Pemerintahan', 'order' => 6],
            ['name' => 'Kepala Seksi Kesejahteraan', 'order' => 7],
            ['name' => 'Kepala Seksi Pelayanan', 'order' => 8],
            ['name' => 'Kepala Dusun I', 'order' => 9],
            ['name' => 'Kepala Dusun II', 'order' => 10],
            ['name' => 'Kepala Dusun III', 'order' => 11],
        ];

        foreach ($positions as $position) {
            ApparatusPosition::firstOrCreate(
                ['name' => $position['name']],
                ['order' => $position['order']]
            );
        }
    }
}

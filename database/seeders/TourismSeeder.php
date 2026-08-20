<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TourismCategory;
use App\Models\TourismFacility;
use Illuminate\Database\Seeder;

class TourismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Wisata Alam', 'description' => 'Destinasi wisata yang menyuguhkan keindahan alam.'],
            ['name' => 'Wisata Budaya', 'description' => 'Destinasi wisata sejarah, seni, dan tradisi lokal.'],
            ['name' => 'Wisata Buatan', 'description' => 'Destinasi wisata rekreasi buatan seperti waterpark atau taman bermain.'],
        ];

        foreach ($categories as $cat) {
            TourismCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        $facilities = [
            ['name' => 'Area Parkir Luas', 'icon' => 'parking-circle'],
            ['name' => 'Toilet Bersih', 'icon' => 'bath'],
            ['name' => 'Mushola', 'icon' => 'book-open'],
            ['name' => 'Warung Makan', 'icon' => 'coffee'],
            ['name' => 'Spot Foto', 'icon' => 'camera'],
            ['name' => 'Gazebo', 'icon' => 'tent'],
        ];

        foreach ($facilities as $fac) {
            TourismFacility::firstOrCreate(['name' => $fac['name']], $fac);
        }

        // We can create dummy tourisms via factory when needed in tests
    }
}

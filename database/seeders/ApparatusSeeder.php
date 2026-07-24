<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Apparatus;
use App\Models\ApparatusPosition;
use Illuminate\Database\Seeder;

final class ApparatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = ApparatusPosition::all();

        if ($positions->isEmpty()) {
            $this->command->warn('Apparatus positions not found. Please run ApparatusPositionSeeder first.');

            return;
        }

        // Create 1 apparatus for each position to ensure a complete hierarchy
        foreach ($positions as $position) {
            Apparatus::factory()->create([
                'position_id' => $position->id,
            ]);
        }

        // Add some random apparatuses, maybe some inactive ones or people who share a position (like staff)
        Apparatus::factory()->count(5)->create([
            'position_id' => $positions->random()->id,
            'is_active' => false,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ActivityLog;
use Illuminate\Database\Seeder;

final class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limit production for development safety (max 15 rows)
        ActivityLog::factory(15)->create();
    }
}

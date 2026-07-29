<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StatisticCategory;
use App\Models\VillageStatistic;
use Illuminate\Database\Seeder;

final class VillageStatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = StatisticCategory::all();

        if ($categories->isEmpty()) {
            return;
        }

        VillageStatistic::factory(50)->recycle($categories)->create();
    }
}

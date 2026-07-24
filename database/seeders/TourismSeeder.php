<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tourism;
use App\Models\TourismCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

final class TourismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = TourismCategory::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        Tourism::factory(10)->recycle($users)->recycle($categories)->create();
    }
}

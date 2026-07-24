<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

final class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        Video::factory()
            ->count(10)
            ->for($user)
            ->create();
    }
}

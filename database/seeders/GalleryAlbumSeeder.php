<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use App\Models\User;
use Illuminate\Database\Seeder;

final class GalleryAlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        GalleryAlbum::factory()
            ->count(5)
            ->for($user)
            ->create();
    }
}

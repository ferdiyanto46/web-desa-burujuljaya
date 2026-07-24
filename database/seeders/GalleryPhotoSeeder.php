<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

final class GalleryPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $albums = GalleryAlbum::all();

        if ($albums->isEmpty()) {
            return;
        }

        foreach ($albums as $album) {
            GalleryPhoto::factory()
                ->count(random_int(3, 8))
                ->for($album, 'album')
                ->create();
        }
    }
}

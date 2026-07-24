<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\GalleryAlbum;
use Illuminate\Support\Facades\Storage;

final class GalleryAlbumObserver
{
    /**
     * Handle the GalleryAlbum "force deleted" event.
     */
    public function forceDeleted(GalleryAlbum $galleryAlbum): void
    {
        // Delete all child photos to trigger their observers (which deletes their physical files)
        // We iterate and delete them individually rather than bulk delete to ensure eloquent events fire.
        $galleryAlbum->photos()->each(function ($photo) {
            $photo->delete();
        });

        if ($galleryAlbum->cover_photo) {
            Storage::disk('public')->delete($galleryAlbum->cover_photo);
        }
    }
}

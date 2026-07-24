<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\GalleryPhoto;
use Illuminate\Support\Facades\Storage;

final class GalleryPhotoObserver
{
    /**
     * Handle the GalleryPhoto "deleted" event.
     */
    public function deleted(GalleryPhoto $galleryPhoto): void
    {
        if ($galleryPhoto->photo_path) {
            Storage::disk('public')->delete($galleryPhoto->photo_path);
        }
    }
}

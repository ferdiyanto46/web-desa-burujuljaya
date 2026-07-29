<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

final class BannerObserver
{
    /**
     * Handle events after all transactions are committed.
     */
    public bool $afterCommit = true;

    /**
     * Handle the Banner "updated" event.
     */
    public function updated(Banner $banner): void
    {
        if ($banner->wasChanged('image_path')) {
            $originalImage = $banner->getOriginal('image_path');

            if (is_string($originalImage)) {
                Storage::disk('public')->delete($originalImage);
            }
        }
    }

    /**
     * Handle the Banner "deleted" event.
     */
    public function deleted(Banner $banner): void
    {
        // Banners do not use SoftDeletes, so deleted() means hard delete.
        Storage::disk('public')->delete($banner->image_path);
    }
}

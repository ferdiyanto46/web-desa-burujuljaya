<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Umkm;
use Illuminate\Support\Facades\Storage;

final class UmkmObserver
{
    /**
     * Handle the Umkm "forceDeleted" event.
     */
    public function forceDeleted(Umkm $umkm): void
    {
        if ($umkm->cover_image) {
            Storage::disk('public')->delete($umkm->cover_image);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Tourism;
use Illuminate\Support\Facades\Storage;

final class TourismObserver
{
    /**
     * Handle the Tourism "forceDeleted" event.
     */
    public function forceDeleted(Tourism $tourism): void
    {
        if ($tourism->cover_image) {
            Storage::disk('public')->delete($tourism->cover_image);
        }
    }
}

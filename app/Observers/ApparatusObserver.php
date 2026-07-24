<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Apparatus;
use Illuminate\Support\Facades\Storage;

final class ApparatusObserver
{
    /**
     * Handle the Apparatus "updating" event.
     */
    public function updating(Apparatus $apparatus): void
    {
        if ($apparatus->isDirty('photo_path') && $apparatus->getOriginal('photo_path')) {
            $originalPhoto = (string) $apparatus->getOriginal('photo_path');

            if (Storage::disk('public')->exists($originalPhoto)) {
                Storage::disk('public')->delete($originalPhoto);
            }
        }
    }

    /**
     * Handle the Apparatus "force deleted" event.
     */
    public function forceDeleted(Apparatus $apparatus): void
    {
        if ($apparatus->photo_path && Storage::disk('public')->exists($apparatus->photo_path)) {
            Storage::disk('public')->delete($apparatus->photo_path);
        }
    }
}

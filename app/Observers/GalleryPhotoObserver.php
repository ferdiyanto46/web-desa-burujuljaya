<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\GalleryPhoto;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class GalleryPhotoObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(GalleryPhoto $photo): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: 'Mengunggah foto ke album galeri',
            subject: $photo,
            logName: 'gallery_photo',
        );
    }

    public function updated(GalleryPhoto $photo): void
    {
        if ($photo->wasChanged('photo_path')) {
            $originalPhoto = $photo->getOriginal('photo_path');
            Storage::disk('public')->delete($originalPhoto);
        }

        $this->activityLogService->log(
            event: 'updated',
            description: 'Memperbarui foto di album galeri',
            subject: $photo,
            properties: [
                'old' => $photo->getOriginal(),
                'attributes' => $photo->getChanges(),
            ],
            logName: 'gallery_photo',
        );
    }

    public function deleted(GalleryPhoto $photo): void
    {
        Storage::disk('public')->delete($photo->photo_path);

        $this->activityLogService->log(
            event: 'deleted',
            description: 'Menghapus foto dari album galeri',
            subject: $photo,
            logName: 'gallery_photo',
        );
    }
}

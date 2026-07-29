<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\GalleryAlbum;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class GalleryAlbumObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(GalleryAlbum $album): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Membuat album galeri: {$album->title}",
            subject: $album,
            logName: 'gallery_album',
        );
    }

    public function updated(GalleryAlbum $album): void
    {
        if ($album->wasChanged('cover_photo')) {
            $originalCover = $album->getOriginal('cover_photo');
            if (is_string($originalCover)) {
                Storage::disk('public')->delete($originalCover);
            }
        }

        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui album galeri: {$album->title}",
            subject: $album,
            properties: [
                'old' => $album->getOriginal(),
                'attributes' => $album->getChanges(),
            ],
            logName: 'gallery_album',
        );
    }

    public function deleted(GalleryAlbum $album): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) album galeri: {$album->title}",
            subject: $album,
            logName: 'gallery_album',
        );
    }

    public function forceDeleted(GalleryAlbum $album): void
    {
        if (is_string($album->cover_photo)) {
            Storage::disk('public')->delete($album->cover_photo);
        }

        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen album galeri: {$album->title}",
            subject: $album,
            logName: 'gallery_album',
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Umkm;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class UmkmObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Umkm $umkm): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Menambahkan data UMKM: {$umkm->name}",
            subject: $umkm,
            logName: 'umkm',
        );
    }

    public function updated(Umkm $umkm): void
    {
        if ($umkm->wasChanged('cover_image')) {
            $originalCover = $umkm->getOriginal('cover_image');
            if (is_string($originalCover)) {
                Storage::disk('public')->delete($originalCover);
            }
        }

        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui data UMKM: {$umkm->name}",
            subject: $umkm,
            properties: [
                'old' => $umkm->getOriginal(),
                'attributes' => $umkm->getChanges(),
            ],
            logName: 'umkm',
        );
    }

    public function deleted(Umkm $umkm): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) data UMKM: {$umkm->name}",
            subject: $umkm,
            logName: 'umkm',
        );
    }

    public function forceDeleted(Umkm $umkm): void
    {
        if (is_string($umkm->cover_image)) {
            Storage::disk('public')->delete($umkm->cover_image);
        }

        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen data UMKM: {$umkm->name}",
            subject: $umkm,
            logName: 'umkm',
        );
    }
}

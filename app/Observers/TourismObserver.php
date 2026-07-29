<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Tourism;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class TourismObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Tourism $tourism): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Menambahkan potensi pariwisata: {$tourism->name}",
            subject: $tourism,
            logName: 'tourism',
        );
    }

    public function updated(Tourism $tourism): void
    {
        if ($tourism->wasChanged('cover_image')) {
            $originalCover = $tourism->getOriginal('cover_image');
            if (is_string($originalCover)) {
                Storage::disk('public')->delete($originalCover);
            }
        }

        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui potensi pariwisata: {$tourism->name}",
            subject: $tourism,
            properties: [
                'old' => $tourism->getOriginal(),
                'attributes' => $tourism->getChanges(),
            ],
            logName: 'tourism',
        );
    }

    public function deleted(Tourism $tourism): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) potensi pariwisata: {$tourism->name}",
            subject: $tourism,
            logName: 'tourism',
        );
    }

    public function forceDeleted(Tourism $tourism): void
    {
        if (is_string($tourism->cover_image)) {
            Storage::disk('public')->delete($tourism->cover_image);
        }

        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen potensi pariwisata: {$tourism->name}",
            subject: $tourism,
            logName: 'tourism',
        );
    }
}

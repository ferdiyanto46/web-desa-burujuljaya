<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Announcement;
use App\Services\ActivityLogService;

final class AnnouncementObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Announcement $announcement): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Menyiarkan pengumuman: {$announcement->title}",
            subject: $announcement,
            logName: 'announcement',
        );
    }

    public function updated(Announcement $announcement): void
    {
        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui pengumuman: {$announcement->title}",
            subject: $announcement,
            properties: [
                'old' => $announcement->getOriginal(),
                'attributes' => $announcement->getChanges(),
            ],
            logName: 'announcement',
        );
    }

    public function deleted(Announcement $announcement): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) pengumuman: {$announcement->title}",
            subject: $announcement,
            logName: 'announcement',
        );
    }

    public function forceDeleted(Announcement $announcement): void
    {
        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen pengumuman: {$announcement->title}",
            subject: $announcement,
            logName: 'announcement',
        );
    }
}

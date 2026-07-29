<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Video;
use App\Services\ActivityLogService;

final class VideoObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Video $video): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Menambahkan video galeri: {$video->title}",
            subject: $video,
            logName: 'video',
        );
    }

    public function updated(Video $video): void
    {
        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui video galeri: {$video->title}",
            subject: $video,
            properties: [
                'old' => $video->getOriginal(),
                'attributes' => $video->getChanges(),
            ],
            logName: 'video',
        );
    }

    public function deleted(Video $video): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) video galeri: {$video->title}",
            subject: $video,
            logName: 'video',
        );
    }

    public function forceDeleted(Video $video): void
    {
        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen video galeri: {$video->title}",
            subject: $video,
            logName: 'video',
        );
    }
}

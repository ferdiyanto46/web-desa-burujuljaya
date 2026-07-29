<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Complaint;
use App\Services\ActivityLogService;

final class ComplaintObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Complaint $complaint): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Menerima pengaduan masyarakat: {$complaint->title}",
            subject: $complaint,
            logName: 'complaint',
        );
    }

    public function updated(Complaint $complaint): void
    {
        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui pengaduan masyarakat: {$complaint->title}",
            subject: $complaint,
            properties: [
                'old' => $complaint->getOriginal(),
                'attributes' => $complaint->getChanges(),
            ],
            logName: 'complaint',
        );
    }

    public function deleted(Complaint $complaint): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus pengaduan masyarakat: {$complaint->title}",
            subject: $complaint,
            logName: 'complaint',
        );
    }
}

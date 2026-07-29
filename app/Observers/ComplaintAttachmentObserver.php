<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ComplaintAttachment;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class ComplaintAttachmentObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(ComplaintAttachment $attachment): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: 'Melampirkan file ke pengaduan masyarakat',
            subject: $attachment,
            logName: 'complaint_attachment',
        );
    }

    public function updated(ComplaintAttachment $attachment): void
    {
        if ($attachment->wasChanged('file_path')) {
            $originalPath = $attachment->getOriginal('file_path');
            Storage::disk('public')->delete($originalPath);
        }

        $this->activityLogService->log(
            event: 'updated',
            description: 'Memperbarui lampiran pengaduan',
            subject: $attachment,
            properties: [
                'old' => $attachment->getOriginal(),
                'attributes' => $attachment->getChanges(),
            ],
            logName: 'complaint_attachment',
        );
    }

    public function deleted(ComplaintAttachment $attachment): void
    {
        Storage::disk('public')->delete($attachment->file_path);

        $this->activityLogService->log(
            event: 'deleted',
            description: 'Menghapus lampiran pengaduan',
            subject: $attachment,
            logName: 'complaint_attachment',
        );
    }
}

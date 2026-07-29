<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Document;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class DocumentObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Document $document): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Mengunggah dokumen desa: {$document->title}",
            subject: $document,
            logName: 'document',
        );
    }

    public function updated(Document $document): void
    {
        if ($document->wasChanged('file_path')) {
            $originalFile = $document->getOriginal('file_path');
            Storage::disk('public')->delete($originalFile);
        }

        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui dokumen desa: {$document->title}",
            subject: $document,
            properties: [
                'old' => $document->getOriginal(),
                'attributes' => $document->getChanges(),
            ],
            logName: 'document',
        );
    }

    public function deleted(Document $document): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) dokumen desa: {$document->title}",
            subject: $document,
            logName: 'document',
        );
    }

    public function forceDeleted(Document $document): void
    {
        Storage::disk('public')->delete($document->file_path);

        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen dokumen desa: {$document->title}",
            subject: $document,
            logName: 'document',
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Apparatus;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class ApparatusObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Apparatus $apparatus): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Menambahkan aparatur desa: {$apparatus->name}",
            subject: $apparatus,
            logName: 'apparatus',
        );
    }

    public function updated(Apparatus $apparatus): void
    {
        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui aparatur desa: {$apparatus->name}",
            subject: $apparatus,
            properties: [
                'old' => $apparatus->getOriginal(),
                'attributes' => $apparatus->getChanges(),
            ],
            logName: 'apparatus',
        );
    }

    public function deleted(Apparatus $apparatus): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) aparatur desa: {$apparatus->name}",
            subject: $apparatus,
            logName: 'apparatus',
        );
    }

    public function forceDeleted(Apparatus $apparatus): void
    {
        if ($apparatus->photo_path && Storage::disk('public')->exists($apparatus->photo_path)) {
            Storage::disk('public')->delete($apparatus->photo_path);
        }

        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen aparatur desa: {$apparatus->name}",
            subject: $apparatus,
            logName: 'apparatus',
        );
    }

    public function restored(Apparatus $apparatus): void
    {
        $this->activityLogService->log(
            event: 'restored',
            description: "Memulihkan aparatur desa: {$apparatus->name}",
            subject: $apparatus,
            logName: 'apparatus',
        );
    }
}

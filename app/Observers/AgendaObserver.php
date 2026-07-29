<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Agenda;
use App\Services\ActivityLogService;

final class AgendaObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Agenda $agenda): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Menjadwalkan agenda baru: {$agenda->title}",
            subject: $agenda,
            logName: 'agenda',
        );
    }

    public function updated(Agenda $agenda): void
    {
        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui agenda: {$agenda->title}",
            subject: $agenda,
            properties: [
                'old' => $agenda->getOriginal(),
                'attributes' => $agenda->getChanges(),
            ],
            logName: 'agenda',
        );
    }

    public function deleted(Agenda $agenda): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) agenda: {$agenda->title}",
            subject: $agenda,
            logName: 'agenda',
        );
    }

    public function forceDeleted(Agenda $agenda): void
    {
        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen agenda: {$agenda->title}",
            subject: $agenda,
            logName: 'agenda',
        );
    }
}

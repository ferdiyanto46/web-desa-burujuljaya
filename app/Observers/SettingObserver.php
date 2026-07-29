<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Setting;
use App\Services\ActivityLogService;

final class SettingObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function updated(Setting $setting): void
    {
        // Don't log encrypted values
        $changes = $setting->getChanges();
        if ($setting->is_encrypted && isset($changes['value'])) {
            $changes['value'] = '********';
        }

        $old = $setting->getOriginal();
        if ($setting->is_encrypted && isset($old['value'])) {
            $old['value'] = '********';
        }

        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui pengaturan: {$setting->label}",
            subject: $setting,
            properties: [
                'old' => $old,
                'attributes' => $changes,
            ],
            logName: 'setting',
        );
    }
}

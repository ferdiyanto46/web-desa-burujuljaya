<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\VillageProfile;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class VillageProfileObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function updated(VillageProfile $profile): void
    {
        if ($profile->wasChanged('logo_path')) {
            $originalLogo = $profile->getOriginal('logo_path');
            if (is_string($originalLogo)) {
                Storage::disk('public')->delete($originalLogo);
            }
        }

        $this->activityLogService->log(
            event: 'updated',
            description: 'Memperbarui profil dan identitas desa',
            subject: $profile,
            properties: [
                'old' => $profile->getOriginal(),
                'attributes' => $profile->getChanges(),
            ],
            logName: 'village_profile',
        );
    }
}

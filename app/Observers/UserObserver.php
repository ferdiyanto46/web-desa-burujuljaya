<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class UserObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(User $user): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Membuat pengguna baru: {$user->name}",
            subject: $user,
            logName: 'user',
        );
    }

    public function updated(User $user): void
    {
        if ($user->wasChanged('avatar')) {
            $originalAvatar = $user->getOriginal('avatar');
            if (is_string($originalAvatar)) {
                Storage::disk('public')->delete($originalAvatar);
            }
        }

        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui profil pengguna: {$user->name}",
            subject: $user,
            properties: [
                'old' => $user->getOriginal(),
                'attributes' => $user->getChanges(),
            ],
            logName: 'user',
        );
    }

    public function deleted(User $user): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) pengguna: {$user->name}",
            subject: $user,
            logName: 'user',
        );
    }

    public function forceDeleted(User $user): void
    {
        if (is_string($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen pengguna: {$user->name}",
            subject: $user,
            logName: 'user',
        );
    }
}

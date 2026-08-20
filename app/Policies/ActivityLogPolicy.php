<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

final class ActivityLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin-desa');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->hasRole('admin-desa');
    }
}

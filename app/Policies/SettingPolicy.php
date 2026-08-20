<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

final class SettingPolicy
{
    /**
     * Determine whether the user can view any settings.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-desa']) || $user->can('manage settings');
    }

    /**
     * Determine whether the user can view the setting.
     */
    public function view(User $user, ?Setting $setting = null): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-desa']) || $user->can('manage settings');
    }

    /**
     * Determine whether the user can update the setting.
     */
    public function update(User $user, ?Setting $setting = null): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin-desa']) || $user->can('manage settings');
    }
}

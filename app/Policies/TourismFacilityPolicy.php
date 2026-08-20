<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TourismFacility;
use App\Models\User;

final class TourismFacilityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.viewAny') || $user->can('view tourism facilities');
    }

    public function view(User $user, TourismFacility $facility): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.view') || $user->can('view tourism facilities');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.create') || $user->can('create tourism facilities');
    }

    public function update(User $user, TourismFacility $facility): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.update') || $user->can('update tourism facilities');
    }

    public function delete(User $user, TourismFacility $facility): bool
    {
        return $user->hasRole('admin-desa') || $user->can('tourism.delete') || $user->can('delete tourism facilities');
    }
}

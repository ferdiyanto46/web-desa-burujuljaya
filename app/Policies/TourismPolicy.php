<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tourism;
use App\Models\User;

final class TourismPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.viewAny') || $user->can('view tourisms');
    }

    public function view(User $user, Tourism $tourism): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.view') || $user->can('view tourisms');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.create') || $user->can('create tourisms');
    }

    public function update(User $user, Tourism $tourism): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.update') || $user->can('update tourisms');
    }

    public function delete(User $user, Tourism $tourism): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.delete') || $user->can('delete tourisms');
    }

    public function restore(User $user, Tourism $tourism): bool
    {
        return $user->hasRole('admin-desa') || $user->can('tourism.restore') || $user->can('restore tourisms');
    }

    public function forceDelete(User $user, Tourism $tourism): bool
    {
        return $user->hasRole('admin-desa') || $user->can('tourism.forceDelete') || $user->can('force delete tourisms');
    }
}

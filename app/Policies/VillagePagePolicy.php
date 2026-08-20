<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VillagePage;

class VillagePagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-pages.viewAny') || $user->can('view village pages');
    }

    public function view(User $user, VillagePage $villagePage): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-pages.view') || $user->can('view village pages');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-pages.create') || $user->can('create village pages');
    }

    public function update(User $user, VillagePage $villagePage): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-pages.update') || $user->can('update village pages');
    }

    public function delete(User $user, VillagePage $villagePage): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-pages.delete') || $user->can('delete village pages');
    }

    public function restore(User $user, VillagePage $villagePage): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-pages.restore') || $user->can('restore village pages');
    }

    public function forceDelete(User $user, VillagePage $villagePage): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-pages.forceDelete') || $user->can('force delete village pages');
    }
}

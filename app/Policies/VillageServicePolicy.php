<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VillageService;

class VillageServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-services.viewAny') || $user->can('view village services');
    }

    public function view(User $user, VillageService $villageService): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-services.view') || $user->can('view village services');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-services.create') || $user->can('create village services');
    }

    public function update(User $user, VillageService $villageService): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-services.update') || $user->can('update village services');
    }

    public function delete(User $user, VillageService $villageService): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-services.delete') || $user->can('delete village services');
    }

    public function restore(User $user, VillageService $villageService): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-services.restore') || $user->can('restore village services');
    }

    public function forceDelete(User $user, VillageService $villageService): bool
    {
        return $user->hasRole('admin-desa') || $user->can('village-services.forceDelete') || $user->can('force delete village services');
    }
}

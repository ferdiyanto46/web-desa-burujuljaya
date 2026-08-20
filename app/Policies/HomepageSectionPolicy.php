<?php

namespace App\Policies;

use App\Models\HomepageSection;
use App\Models\User;

class HomepageSectionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'staff-desa']);
    }

    public function view(User $user, HomepageSection $homepageSection): bool
    {
        return $user->hasRole(['admin-desa', 'staff-desa']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin-desa');
    }

    public function update(User $user, HomepageSection $homepageSection): bool
    {
        return $user->hasRole(['admin-desa', 'staff-desa']);
    }

    public function updateAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'staff-desa']);
    }

    public function delete(User $user, HomepageSection $homepageSection): bool
    {
        return $user->hasRole('admin-desa');
    }

    public function restore(User $user, HomepageSection $homepageSection): bool
    {
        return $user->hasRole('admin-desa');
    }

    public function forceDelete(User $user, HomepageSection $homepageSection): bool
    {
        return $user->hasRole('admin-desa');
    }
}

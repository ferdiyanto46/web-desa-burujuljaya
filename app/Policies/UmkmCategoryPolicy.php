<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\UmkmCategory;
use App\Models\User;

final class UmkmCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.viewAny') || $user->can('view umkm categories');
    }

    public function view(User $user, UmkmCategory $category): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.view') || $user->can('view umkm categories');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.create') || $user->can('create umkm categories');
    }

    public function update(User $user, UmkmCategory $category): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.update') || $user->can('update umkm categories');
    }

    public function delete(User $user, UmkmCategory $category): bool
    {
        return $user->hasRole('admin-desa') || $user->can('umkm.delete') || $user->can('delete umkm categories');
    }
}

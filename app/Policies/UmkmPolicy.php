<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Umkm;
use App\Models\User;

final class UmkmPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.viewAny') || $user->can('view umkm');
    }

    public function view(User $user, Umkm $umkm): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.view') || $user->can('view umkm');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.create') || $user->can('create umkm');
    }

    public function update(User $user, Umkm $umkm): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.update') || $user->can('update umkm');
    }

    public function delete(User $user, Umkm $umkm): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('umkm.delete') || $user->can('delete umkm');
    }

    public function restore(User $user, Umkm $umkm): bool
    {
        return $user->hasRole('admin-desa') || $user->can('umkm.restore') || $user->can('restore umkm');
    }

    public function forceDelete(User $user, Umkm $umkm): bool
    {
        return $user->hasRole('admin-desa') || $user->can('umkm.forceDelete') || $user->can('force delete umkm');
    }
}

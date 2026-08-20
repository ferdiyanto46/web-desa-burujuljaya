<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('galleries.viewAny') || $user->can('view any galleries');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Gallery $gallery): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('galleries.view') || $user->can('view galleries');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('galleries.create') || $user->can('create galleries');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Gallery $gallery): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('galleries.update') || $user->can('update galleries');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Gallery $gallery): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('galleries.delete') || $user->can('delete galleries');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Gallery $gallery): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('galleries.restore') || $user->can('restore galleries');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Gallery $gallery): bool
    {
        return $user->hasRole('admin-desa') || $user->can('galleries.forceDelete') || $user->can('force delete galleries');
    }
}

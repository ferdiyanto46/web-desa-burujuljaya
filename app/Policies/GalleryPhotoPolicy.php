<?php

namespace App\Policies;

use App\Models\GalleryPhoto;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPhotoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('gallery-photos.viewAny') || $user->can('view any gallery photos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GalleryPhoto $galleryPhoto): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('gallery-photos.view') || $user->can('view gallery photos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('gallery-photos.create') || $user->can('create gallery photos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GalleryPhoto $galleryPhoto): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('gallery-photos.update') || $user->can('update gallery photos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GalleryPhoto $galleryPhoto): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('gallery-photos.delete') || $user->can('delete gallery photos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GalleryPhoto $galleryPhoto): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('gallery-photos.restore') || $user->can('restore gallery photos');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GalleryPhoto $galleryPhoto): bool
    {
        return $user->hasRole('admin-desa') || $user->can('gallery-photos.forceDelete') || $user->can('force delete gallery photos');
    }
}

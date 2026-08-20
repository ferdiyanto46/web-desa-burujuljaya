<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ContactInformation;
use App\Models\User;

class ContactInformationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin-desa') || $user->can('contact-information.viewAny') || $user->can('view any contact-information');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContactInformation $contactInformation): bool
    {
        return $user->hasRole('admin-desa') || $user->can('contact-information.view') || $user->can('view contact-information');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin-desa') || $user->can('contact-information.create') || $user->can('create contact-information');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContactInformation $contactInformation): bool
    {
        return $user->hasRole('admin-desa') || $user->can('contact-information.update') || $user->can('update contact-information');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContactInformation $contactInformation): bool
    {
        return $user->hasRole('admin-desa') || $user->can('contact-information.delete') || $user->can('delete contact-information');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContactInformation $contactInformation): bool
    {
        return $user->hasRole('admin-desa') || $user->can('contact-information.restore') || $user->can('restore contact-information');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContactInformation $contactInformation): bool
    {
        return $user->hasRole('admin-desa') || $user->can('contact-information.forceDelete') || $user->can('force delete contact-information');
    }
}

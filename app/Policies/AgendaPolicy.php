<?php

namespace App\Policies;

use App\Models\Agenda;
use App\Models\User;

class AgendaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('agendas.viewAny') || $user->can('view any agendas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agenda $agenda): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('agendas.view') || $user->can('view agendas');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('agendas.create') || $user->can('create agendas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agenda $agenda): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('agendas.update') || $user->can('update agendas') || $user->id === $agenda->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agenda $agenda): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita']) || $user->can('agendas.delete') || $user->can('delete agendas');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agenda $agenda): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita']) || $user->can('agendas.restore') || $user->can('restore agendas');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agenda $agenda): bool
    {
        return $user->hasRole('admin-desa') || $user->can('agendas.forceDelete') || $user->can('force delete agendas');
    }
}

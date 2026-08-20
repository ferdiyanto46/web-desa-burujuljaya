<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('announcements.viewAny') || $user->can('view any announcements');
    }

    public function view(User $user, Announcement $announcement): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('announcements.view') || $user->can('view announcements');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('announcements.create') || $user->can('create announcements');
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('announcements.update') || $user->can('update announcements');
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita']) || $user->can('announcements.delete') || $user->can('delete announcements');
    }

    public function restore(User $user, Announcement $announcement): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita']) || $user->can('announcements.restore') || $user->can('restore announcements');
    }

    public function forceDelete(User $user, Announcement $announcement): bool
    {
        return $user->hasRole('admin-desa') || $user->can('announcements.forceDelete') || $user->can('force delete announcements');
    }
}

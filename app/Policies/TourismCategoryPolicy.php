<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TourismCategory;
use App\Models\User;

final class TourismCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.viewAny') || $user->can('view tourism categories');
    }

    public function view(User $user, TourismCategory $category): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.view') || $user->can('view tourism categories');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.create') || $user->can('create tourism categories');
    }

    public function update(User $user, TourismCategory $category): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('tourism.update') || $user->can('update tourism categories');
    }

    public function delete(User $user, TourismCategory $category): bool
    {
        return $user->hasRole('admin-desa') || $user->can('tourism.delete') || $user->can('delete tourism categories');
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TourismPhoto;
use App\Models\User;

final class TourismPhotoPolicy
{
    public function delete(User $user, TourismPhoto $photo): bool
    {
        return $user->hasRole('admin-desa') || $user->can('tourism.delete') || $user->can('delete tourisms');
    }
}

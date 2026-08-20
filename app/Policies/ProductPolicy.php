<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

final class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.viewAny') || $user->can('view products');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.view') || $user->can('view products');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.create') || $user->can('create products');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.update') || $user->can('update products');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.delete') || $user->can('delete products');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->hasRole('admin-desa') || $user->can('products.restore') || $user->can('restore products');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->hasRole('admin-desa') || $user->can('products.forceDelete') || $user->can('force delete products');
    }
}

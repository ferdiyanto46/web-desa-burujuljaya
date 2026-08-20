<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProductCategory;
use App\Models\User;

final class ProductCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.viewAny') || $user->can('view product categories');
    }

    public function view(User $user, ProductCategory $category): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.view') || $user->can('view product categories');
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.create') || $user->can('create product categories');
    }

    public function update(User $user, ProductCategory $category): bool
    {
        return $user->hasRole(['admin-desa', 'operator']) || $user->can('products.update') || $user->can('update product categories');
    }

    public function delete(User $user, ProductCategory $category): bool
    {
        return $user->hasRole('admin-desa') || $user->can('products.delete') || $user->can('delete product categories');
    }
}

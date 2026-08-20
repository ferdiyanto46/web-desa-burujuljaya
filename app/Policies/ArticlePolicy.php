<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('articles.viewAny') || $user->can('view any articles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('articles.view') || $user->can('view articles');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('articles.create') || $user->can('create articles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita', 'operator']) || $user->can('articles.update') || $user->can('update articles');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita']) || $user->can('articles.delete') || $user->can('delete articles');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Article $article): bool
    {
        return $user->hasRole(['admin-desa', 'editor-berita']) || $user->can('articles.restore') || $user->can('restore articles');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article): bool
    {
        return $user->hasRole('admin-desa') || $user->can('articles.forceDelete') || $user->can('force delete articles');
    }
}

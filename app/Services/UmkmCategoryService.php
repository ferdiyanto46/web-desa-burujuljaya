<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UmkmCategory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class UmkmCategoryService
{
    /**
     * Get paginated categories.
     */
    public function getPaginated(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = UmkmCategory::query()
            ->withCount('umkms');

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get all categories for select dropdowns.
     */
    public function getAllForSelect(): Collection
    {
        return UmkmCategory::orderBy('name')->pluck('name', 'id');
    }

    /**
     * Store a new category.
     *
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): UmkmCategory
    {
        return UmkmCategory::create($data);
    }

    /**
     * Update a category.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(UmkmCategory $category, array $data): UmkmCategory
    {
        $category->update($data);

        return $category->fresh();
    }

    /**
     * Delete a category.
     */
    public function delete(UmkmCategory $category): bool
    {
        if ($category->umkms()->exists()) {
            throw new \Exception('Kategori tidak dapat dihapus karena masih memiliki UMKM.');
        }

        return $category->delete();
    }
}

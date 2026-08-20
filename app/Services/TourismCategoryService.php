<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TourismCategory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\LengthAwarePaginator;

final class TourismCategoryService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Get paginated categories.
     */
    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = TourismCategory::query();

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get all categories.
     */
    public function getAll()
    {
        return TourismCategory::orderBy('name')->get();
    }

    /**
     * Store a new category.
     */
    public function store(array $data): TourismCategory
    {
        return $this->db->transaction(function () use ($data) {
            return TourismCategory::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);
        });
    }

    /**
     * Update an existing category.
     */
    public function update(TourismCategory $category, array $data): TourismCategory
    {
        return $this->db->transaction(function () use ($category, $data) {
            $category->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            return $category;
        });
    }

    /**
     * Soft delete a category.
     */
    public function delete(TourismCategory $category): void
    {
        $this->db->transaction(function () use ($category) {
            $category->delete();
        });
    }
}

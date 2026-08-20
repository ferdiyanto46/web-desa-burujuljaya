<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductCategory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductCategoryService
{
    public function __construct(private readonly DatabaseManager $db) {}

    /**
     * Get paginated product categories.
     */
    public function getPaginated(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        $query = ProductCategory::query();

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Store a new product category.
     *
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): ProductCategory
    {
        return $this->db->transaction(function () use ($data) {
            return ProductCategory::create($data);
        });
    }

    /**
     * Update an existing product category.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(ProductCategory $category, array $data): ProductCategory
    {
        return $this->db->transaction(function () use ($category, $data) {
            $category->update($data);

            return $category;
        });
    }

    /**
     * Delete a product category.
     */
    public function delete(ProductCategory $category): bool
    {
        return $this->db->transaction(function () use ($category) {
            return $category->delete();
        });
    }
}

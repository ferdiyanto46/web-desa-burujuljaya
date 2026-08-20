<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Mews\Purifier\Facades\Purifier;

final class ProductService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Get paginated Products for admin area.
     */
    public function getPaginated(int $perPage = 10, string $search = '', string|int|null $categoryId = null): LengthAwarePaginator
    {
        $query = Product::query()->withDetails();

        if ($search) {
            $query->search($search);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get paginated Products for public area.
     */
    public function getPublicPaginated(int $perPage = 12, string|int|null $categoryId = null, string $search = ''): LengthAwarePaginator
    {
        $query = Product::query()
            ->withDetails()
            ->published();

        if ($search) {
            $query->search($search);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Store a new Product.
     *
     * @param  array<string, mixed>  $data
     * @param  UploadedFile[]  $gallery
     */
    public function store(array $data, ?UploadedFile $featuredImage = null, array $gallery = []): Product
    {
        return $this->db->transaction(function () use ($data, $featuredImage, $gallery) {
            $product = new Product;

            if (isset($data['description'])) {
                $data['description'] = Purifier::clean($data['description']);
            }

            if ($featuredImage) {
                $data['featured_image_path'] = $this->mediaService->store($featuredImage, 'products/featured');
            }

            $data['user_id'] = $data['user_id'] ?? auth()->id();

            $product->fill($data);
            $product->save();

            if (! empty($gallery)) {
                $this->syncGallery($product, $gallery);
            }

            return $product;
        });
    }

    /**
     * Update an existing Product.
     *
     * @param  array<string, mixed>  $data
     * @param  UploadedFile[]  $gallery
     */
    public function update(Product $product, array $data, ?UploadedFile $featuredImage = null, array $gallery = []): Product
    {
        return $this->db->transaction(function () use ($product, $data, $featuredImage, $gallery) {
            if (isset($data['description'])) {
                $data['description'] = Purifier::clean($data['description']);
            }

            if ($featuredImage) {
                $data['featured_image_path'] = $this->mediaService->replace(
                    $featuredImage,
                    $product->featured_image_path,
                    'products/featured'
                );
            }

            $product->fill($data);
            $product->save();

            if (! empty($gallery)) {
                $this->syncGallery($product, $gallery);
            }

            return $product->fresh();
        });
    }

    /**
     * Soft delete a Product.
     */
    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    /**
     * Force delete a Product and its media.
     */
    public function forceDelete(Product $product): bool
    {
        return $this->db->transaction(function () use ($product) {
            $this->mediaService->delete($product->featured_image_path);

            foreach ($product->photos as $photo) {
                $this->mediaService->delete($photo->image_path);
            }

            return $product->forceDelete();
        });
    }

    /**
     * Restore a soft deleted Product.
     */
    public function restore(Product $product): bool
    {
        return $product->restore();
    }

    /**
     * Increment the view count of a Product.
     */
    public function incrementViewCount(Product $product): void
    {
        $product->increment('view_count');
    }

    /**
     * Sync gallery photos for the Product.
     *
     * @param  UploadedFile[]  $photos
     */
    private function syncGallery(Product $product, array $photos): void
    {
        $order = $product->photos()->max('sort_order') ?? 0;

        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $path = $this->mediaService->store($photo, 'products/gallery');

                if ($path) {
                    $order++;
                    $product->photos()->create([
                        'image_path' => $path,
                        'sort_order' => $order,
                    ]);
                }
            }
        }
    }
}

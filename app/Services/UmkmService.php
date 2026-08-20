<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Umkm;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Mews\Purifier\Facades\Purifier;

final class UmkmService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Get paginated UMKM for admin area.
     */
    public function getPaginated(int $perPage = 10, string $search = '', string|int|null $categoryId = null): LengthAwarePaginator
    {
        $query = Umkm::query()->with('category');

        if ($search) {
            $query->where('title', 'like', '%'.$search.'%');
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get paginated UMKM for public area.
     */
    public function getPublicPaginated(int $perPage = 12, string|int|null $categoryId = null, string $search = ''): LengthAwarePaginator
    {
        $query = Umkm::query()
            ->with('category')
            ->published();

        if ($search) {
            $query->where('title', 'like', '%'.$search.'%');
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Store a new UMKM.
     *
     * @param  array<string, mixed>  $data
     * @param  UploadedFile[]  $gallery
     */
    public function store(array $data, ?UploadedFile $featuredImage = null, array $gallery = []): Umkm
    {
        return $this->db->transaction(function () use ($data, $featuredImage, $gallery) {
            $umkm = new Umkm;

            if (isset($data['description'])) {
                $data['description'] = Purifier::clean($data['description']);
            }

            if ($featuredImage) {
                $data['featured_image_path'] = $this->mediaService->store($featuredImage, 'umkm/featured');
            }

            $data['user_id'] = $data['user_id'] ?? auth()->id();

            $umkm->fill($data);
            $umkm->save();

            if (! empty($gallery)) {
                $this->syncPhotos($umkm, $gallery);
            }

            return $umkm;
        });
    }

    /**
     * Update an existing UMKM.
     *
     * @param  array<string, mixed>  $data
     * @param  UploadedFile[]  $gallery
     */
    public function update(Umkm $umkm, array $data, ?UploadedFile $featuredImage = null, array $gallery = []): Umkm
    {
        return $this->db->transaction(function () use ($umkm, $data, $featuredImage, $gallery) {
            if (isset($data['description'])) {
                $data['description'] = Purifier::clean($data['description']);
            }

            if ($featuredImage) {
                $data['featured_image_path'] = $this->mediaService->replace(
                    $featuredImage,
                    $umkm->featured_image_path,
                    'umkm/featured'
                );
            }

            $umkm->fill($data);
            $umkm->save();

            if (! empty($gallery)) {
                $this->syncPhotos($umkm, $gallery);
            }

            return $umkm->fresh();
        });
    }

    /**
     * Soft delete an UMKM.
     */
    public function delete(Umkm $umkm): bool
    {
        return $umkm->delete();
    }

    /**
     * Force delete an UMKM and its media.
     */
    public function forceDelete(Umkm $umkm): bool
    {
        return $this->db->transaction(function () use ($umkm) {
            $this->mediaService->delete($umkm->featured_image_path);

            foreach ($umkm->photos as $photo) {
                $this->mediaService->delete($photo->file_path);
            }

            return $umkm->forceDelete();
        });
    }

    /**
     * Restore a soft deleted UMKM.
     */
    public function restore(Umkm $umkm): bool
    {
        return $umkm->restore();
    }

    /**
     * Increment the view count of an UMKM.
     */
    public function incrementViewCount(Umkm $umkm): void
    {
        $umkm->increment('view_count');
    }

    /**
     * Sync photos for the UMKM.
     *
     * @param  UploadedFile[]  $photos
     */
    private function syncPhotos(Umkm $umkm, array $photos): void
    {
        $order = $umkm->photos()->max('order_column') ?? 0;

        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $path = $this->mediaService->store($photo, 'umkm/gallery');

                if ($path) {
                    $order++;
                    $umkm->photos()->create([
                        'file_path' => $path,
                        'order_column' => $order,
                    ]);
                }
            }
        }
    }
}

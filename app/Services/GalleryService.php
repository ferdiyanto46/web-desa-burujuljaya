<?php

namespace App\Services;

use App\Enums\PublicationStatus;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GalleryService
{
    public function __construct(
        private readonly MediaService $mediaService
    ) {}

    /**
     * Get paginated galleries for admin with filters.
     */
    public function getAdminPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Gallery::query()
            ->with(['author'])
            ->withCount('photos');

        if (! empty($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['is_pinned']) && $filters['is_pinned'] !== '') {
            $query->where('is_pinned', filter_var($filters['is_pinned'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get paginated galleries for public view.
     */
    public function getPublicPaginated(int $perPage = 12): LengthAwarePaginator
    {
        return Gallery::query()
            ->where('status', PublicationStatus::Published)
            ->withCount('photos')
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->paginate($perPage);
    }

    /**
     * Create a new gallery.
     */
    public function createGallery(array $data, User $author): Gallery
    {
        return DB::transaction(function () use ($data, $author) {
            $gallery = new Gallery;
            $gallery->fill($data);
            $gallery->user_id = $author->id;

            // Set published_at if status is published
            if ($gallery->status === PublicationStatus::Published) {
                $gallery->published_at = now();
            }

            // Handle featured image upload
            if (isset($data['featured_image'])) {
                $gallery->featured_image_path = $this->mediaService->store(
                    $data['featured_image'],
                    'galleries'
                );
            }

            $gallery->save();

            return $gallery;
        });
    }

    /**
     * Update an existing gallery.
     */
    public function updateGallery(Gallery $gallery, array $data): Gallery
    {
        return DB::transaction(function () use ($gallery, $data) {
            // Handle status change to published
            if (isset($data['status']) && $data['status'] !== $gallery->status->value) {
                if ($data['status'] === PublicationStatus::Published->value) {
                    $gallery->published_at = $gallery->published_at ?? now();
                }
            }

            $gallery->fill($data);

            // Handle featured image update
            if (isset($data['featured_image'])) {
                if ($gallery->featured_image_path) {
                    $this->mediaService->delete($gallery->featured_image_path);
                }

                $gallery->featured_image_path = $this->mediaService->store(
                    $data['featured_image'],
                    'galleries'
                );
            }

            $gallery->save();

            return $gallery;
        });
    }

    /**
     * Soft delete a gallery.
     */
    public function deleteGallery(Gallery $gallery): void
    {
        $gallery->delete(); // Soft delete, media kept
    }

    /**
     * Restore a soft-deleted gallery.
     */
    public function restoreGallery(Gallery $gallery): void
    {
        $gallery->restore();
    }

    /**
     * Permanently delete a gallery and all its media.
     */
    public function forceDeleteGallery(Gallery $gallery): void
    {
        DB::transaction(function () use ($gallery) {
            if ($gallery->featured_image_path) {
                $this->mediaService->delete($gallery->featured_image_path);
            }

            // Note: Photos will be force deleted by observers or we need to explicitly force delete them.
            // Since we use restricted foreign key, we must delete photos first.
            $photos = $gallery->photos()->withTrashed()->get();
            foreach ($photos as $photo) {
                // Delete actual photo files
                $this->mediaService->delete($photo->image_path);
                $photo->forceDelete();
            }

            $gallery->forceDelete();
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PublicationStatus;
use App\Models\Tourism;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\LengthAwarePaginator;

final class TourismService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Get paginated tourisms for admin.
     */
    public function getAdminPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Tourism::query()->withDetails();

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get paginated published tourisms for frontend.
     */
    public function getPublicPaginated(array $filters = [], int $perPage = 9): LengthAwarePaginator
    {
        $query = Tourism::query()->withDetails()->published();

        if (! empty($filters['cari'])) {
            $query->search($filters['cari']);
        }

        if (! empty($filters['kategori'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('slug', $filters['kategori']);
            });
        }

        if (! empty($filters['fasilitas'])) {
            $query->whereHas('facilities', function ($q) use ($filters) {
                $q->whereIn('tourism_facilities.id', (array) $filters['fasilitas']);
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Store a new tourism.
     */
    public function store(array $data, int $userId): Tourism
    {
        return $this->db->transaction(function () use ($data, $userId) {
            $tourism = new Tourism;
            $tourism->user_id = $userId;

            $this->fillTourismData($tourism, $data);

            if (isset($data['featured_image'])) {
                $tourism->featured_image_path = $this->mediaService->store($data['featured_image'], 'tourism');
            }

            if (isset($data['og_image'])) {
                $tourism->og_image = $this->mediaService->store($data['og_image'], 'tourism/seo');
            }

            $tourism->save();

            if (isset($data['facilities']) && is_array($data['facilities'])) {
                $tourism->facilities()->sync($data['facilities']);
            }

            $this->handleGalleryUploads($tourism, $data['gallery'] ?? []);

            return $tourism;
        });
    }

    /**
     * Update an existing tourism.
     */
    public function update(Tourism $tourism, array $data): Tourism
    {
        return $this->db->transaction(function () use ($tourism, $data) {
            $this->fillTourismData($tourism, $data);

            if (isset($data['featured_image'])) {
                $tourism->featured_image_path = $this->mediaService->replace($data['featured_image'], $tourism->featured_image_path, 'tourism');
            }

            if (isset($data['og_image'])) {
                $tourism->og_image = $this->mediaService->replace($data['og_image'], $tourism->og_image, 'tourism/seo');
            }

            $tourism->save();

            if (isset($data['facilities']) && is_array($data['facilities'])) {
                $tourism->facilities()->sync($data['facilities']);
            }

            $this->handleGalleryUploads($tourism, $data['gallery'] ?? []);

            return $tourism;
        });
    }

    /**
     * Delete a single gallery photo.
     */
    public function deletePhoto(Tourism $tourism, string $photoUlid): void
    {
        $this->db->transaction(function () use ($tourism, $photoUlid) {
            $photo = $tourism->photos()->where('ulid', $photoUlid)->firstOrFail();
            $this->mediaService->delete($photo->file_path);
            $photo->delete();
        });
    }

    /**
     * Soft delete a tourism.
     */
    public function delete(Tourism $tourism): void
    {
        $this->db->transaction(function () use ($tourism) {
            $tourism->delete();
        });
    }

    /**
     * Restore a soft deleted tourism.
     */
    public function restore(Tourism $tourism): void
    {
        $this->db->transaction(function () use ($tourism) {
            $tourism->restore();
        });
    }

    /**
     * Force delete a tourism and its media.
     */
    public function forceDelete(Tourism $tourism): void
    {
        $this->db->transaction(function () use ($tourism) {
            if ($tourism->featured_image_path) {
                $this->mediaService->delete($tourism->featured_image_path);
            }

            if ($tourism->og_image) {
                $this->mediaService->delete($tourism->og_image);
            }

            foreach ($tourism->photos as $photo) {
                $this->mediaService->delete($photo->file_path);
            }

            $tourism->forceDelete();
        });
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(Tourism $tourism): void
    {
        $tourism->increment('view_count');
    }

    /**
     * Helper to fill standard data.
     */
    private function fillTourismData(Tourism $tourism, array $data): void
    {
        $tourism->fill([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'description' => $data['description'] ?? null,
            'address' => $data['address'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'google_maps_url' => $data['google_maps_url'] ?? null,
            'opening_hours' => $data['opening_hours'] ?? null,
            'ticket_price' => $data['ticket_price'] ?? null,
            'contact_person' => $data['contact_person'] ?? null,
            'phone' => $data['phone'] ?? null,
            'website' => $data['website'] ?? null,
            'status' => PublicationStatus::from($data['status']),
            'published_at' => $data['status'] === PublicationStatus::Published->value ? ($tourism->published_at ?? now()) : null,
            'is_featured' => $data['is_featured'] ?? false,
            'featured_order' => $data['featured_order'] ?? 0,
            'is_pinned' => $data['is_pinned'] ?? false,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'canonical_url' => $data['canonical_url'] ?? null,
        ]);
    }

    /**
     * Helper to handle gallery uploads.
     */
    private function handleGalleryUploads(Tourism $tourism, array $files): void
    {
        $order = $tourism->photos()->max('order') ?? 0;

        foreach ($files as $file) {
            if ($file) {
                $order++;
                $path = $this->mediaService->store($file, 'tourism/gallery');
                if ($path) {
                    $tourism->photos()->create([
                        'file_path' => $path,
                        'order' => $order,
                    ]);
                }
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VillagePage;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\LengthAwarePaginator;

class VillagePageService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * @return LengthAwarePaginator<VillagePage>
     */
    public function getPaginatedForAdmin(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return VillagePage::query()
            ->search($search)
            ->withDetails()
            ->orderBy('sort_order', 'asc')
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return LengthAwarePaginator<VillagePage>
     */
    public function getPaginatedForPublic(?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        return VillagePage::query()
            ->published()
            ->search($search)
            ->withDetails()
            ->ordered()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data, int $userId): VillagePage
    {
        return $this->db->transaction(function () use ($data, $userId) {
            $data['user_id'] = $userId;

            if (isset($data['featured_image'])) {
                $data['featured_image_path'] = $this->mediaService->store(
                    $data['featured_image'],
                    'village_pages/featured'
                );
            }

            return VillagePage::create($data);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(VillagePage $page, array $data): VillagePage
    {
        return $this->db->transaction(function () use ($page, $data) {
            if (isset($data['featured_image'])) {
                $data['featured_image_path'] = $this->mediaService->replace(
                    $data['featured_image'],
                    $page->featured_image_path,
                    'village_pages/featured'
                );
            } elseif (array_key_exists('featured_image', $data) && $data['featured_image'] === null) {
                if ($page->featured_image_path) {
                    $this->mediaService->delete($page->featured_image_path);
                    $data['featured_image_path'] = null;
                }
            }

            $page->update($data);

            return $page;
        });
    }

    public function delete(VillagePage $page): bool
    {
        return $this->db->transaction(function () use ($page) {
            return $page->delete();
        });
    }

    public function restore(VillagePage $page): bool
    {
        return $this->db->transaction(function () use ($page) {
            return $page->restore();
        });
    }

    public function forceDelete(VillagePage $page): bool
    {
        return $this->db->transaction(function () use ($page) {
            if ($page->featured_image_path) {
                $this->mediaService->delete($page->featured_image_path);
            }

            return $page->forceDelete();
        });
    }
}

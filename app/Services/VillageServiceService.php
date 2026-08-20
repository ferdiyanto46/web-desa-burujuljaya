<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VillageService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class VillageServiceService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * @return LengthAwarePaginator<int, VillageService>
     */
    public function getPaginatedForAdmin(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return VillageService::query()
            ->search($search)
            ->withDetails()
            ->ordered()
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return LengthAwarePaginator<int, VillageService>
     */
    public function getPaginatedForPublic(?string $search = null, int $perPage = 12): LengthAwarePaginator
    {
        return VillageService::query()
            ->published()
            ->search($search)
            ->withDetails()
            ->ordered()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getBySlug(string $slug): VillageService
    {
        return VillageService::query()
            ->published()
            ->withDetails()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data, int $userId): VillageService
    {
        return $this->db->transaction(function () use ($data, $userId) {
            $data['user_id'] = $userId;

            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $data['featured_image_path'] = $this->mediaService->store(
                    $data['featured_image'],
                    'village_services/featured'
                );
            }

            if (isset($data['downloadable_form']) && $data['downloadable_form'] instanceof UploadedFile) {
                /** @var UploadedFile $file */
                $file = $data['downloadable_form'];
                $data['downloadable_form_path'] = $this->mediaService->store(
                    $file,
                    'village_services/forms'
                );
                $data['downloadable_form_name'] = $file->getClientOriginalName();
                $data['downloadable_form_disk'] = 'public';
                $data['downloadable_form_size'] = $file->getSize();
                $data['downloadable_form_mime'] = $file->getMimeType();
                $data['downloadable_form_extension'] = $file->getClientOriginalExtension();
            }

            return VillageService::create($data);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(VillageService $villageService, array $data): VillageService
    {
        return $this->db->transaction(function () use ($villageService, $data) {
            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $data['featured_image_path'] = $this->mediaService->replace(
                    $data['featured_image'],
                    $villageService->featured_image_path,
                    'village_services/featured'
                );
            } elseif (array_key_exists('featured_image', $data) && $data['featured_image'] === null) {
                if ($villageService->featured_image_path) {
                    $this->mediaService->delete($villageService->featured_image_path);
                    $data['featured_image_path'] = null;
                }
            }

            if (isset($data['downloadable_form']) && $data['downloadable_form'] instanceof UploadedFile) {
                /** @var UploadedFile $file */
                $file = $data['downloadable_form'];
                $data['downloadable_form_path'] = $this->mediaService->replace(
                    $file,
                    $villageService->downloadable_form_path,
                    'village_services/forms'
                );
                $data['downloadable_form_name'] = $file->getClientOriginalName();
                $data['downloadable_form_disk'] = 'public';
                $data['downloadable_form_size'] = $file->getSize();
                $data['downloadable_form_mime'] = $file->getMimeType();
                $data['downloadable_form_extension'] = $file->getClientOriginalExtension();
            } elseif (array_key_exists('downloadable_form', $data) && $data['downloadable_form'] === null) {
                if ($villageService->downloadable_form_path) {
                    $this->mediaService->delete($villageService->downloadable_form_path);
                    $data['downloadable_form_path'] = null;
                    $data['downloadable_form_name'] = null;
                    $data['downloadable_form_disk'] = null;
                    $data['downloadable_form_size'] = null;
                    $data['downloadable_form_mime'] = null;
                    $data['downloadable_form_extension'] = null;
                }
            }

            $villageService->update($data);

            return $villageService;
        });
    }

    public function delete(VillageService $villageService): bool
    {
        return $this->db->transaction(function () use ($villageService) {
            return $villageService->delete();
        });
    }

    public function restore(VillageService $villageService): bool
    {
        return $this->db->transaction(function () use ($villageService) {
            return $villageService->restore();
        });
    }

    public function forceDelete(VillageService $villageService): bool
    {
        return $this->db->transaction(function () use ($villageService) {
            if ($villageService->featured_image_path) {
                $this->mediaService->delete($villageService->featured_image_path);
            }
            if ($villageService->downloadable_form_path) {
                $this->mediaService->delete($villageService->downloadable_form_path);
            }

            return $villageService->forceDelete();
        });
    }
}

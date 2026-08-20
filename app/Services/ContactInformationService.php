<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContactInformation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ContactInformationService
{
    public function __construct(
        private readonly MediaService $mediaService
    ) {}

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, ContactInformation>
     */
    public function getPaginatedForAdmin(array $filters = []): LengthAwarePaginator
    {
        return ContactInformation::query()
            ->search($filters['search'] ?? null)
            ->withDetails()
            ->orderBy('sort_order', 'asc')
            ->latest('id')
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, ContactInformation>
     */
    public function getPaginatedForPublic(array $filters = []): LengthAwarePaginator
    {
        return ContactInformation::query()
            ->published()
            ->search($filters['search'] ?? null)
            ->withDetails()
            ->ordered()
            ->paginate($filters['per_page'] ?? 12)
            ->withQueryString();
    }

    public function getBySlug(string $slug): ContactInformation
    {
        return ContactInformation::query()
            ->published()
            ->withDetails()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): ContactInformation
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = auth()->id();

            $contactInformation = ContactInformation::create($data);

            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $path = $this->mediaService->store(
                    $data['featured_image'],
                    'contact-information'
                );
                $contactInformation->update(['featured_image_path' => $path]);
            }

            return $contactInformation;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(string $ulid, array $data): ContactInformation
    {
        return DB::transaction(function () use ($ulid, $data) {
            $contactInformation = ContactInformation::where('ulid', $ulid)->firstOrFail();

            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $path = $this->mediaService->replace(
                    $data['featured_image'],
                    $contactInformation->featured_image_path,
                    'contact-information'
                );
                $data['featured_image_path'] = $path;
            } elseif (array_key_exists('featured_image', $data) && $data['featured_image'] === null && $contactInformation->featured_image_path) {
                $this->mediaService->delete($contactInformation->featured_image_path);
                $data['featured_image_path'] = null;
            }

            $contactInformation->update($data);

            return $contactInformation;
        });
    }

    public function delete(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $contactInformation = ContactInformation::where('ulid', $ulid)->firstOrFail();

            return $contactInformation->delete();
        });
    }

    public function restore(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $contactInformation = ContactInformation::withTrashed()->where('ulid', $ulid)->firstOrFail();

            return $contactInformation->restore();
        });
    }

    public function forceDelete(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $contactInformation = ContactInformation::withTrashed()->where('ulid', $ulid)->firstOrFail();

            if ($contactInformation->featured_image_path) {
                $this->mediaService->delete($contactInformation->featured_image_path);
            }

            return $contactInformation->forceDelete();
        });
    }
}

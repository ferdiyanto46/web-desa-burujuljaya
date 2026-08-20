<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Faq;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class FaqService
{
    /**
     * Get paginated FAQs for admin.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Faq>
     */
    public function getPaginatedForAdmin(array $filters = []): LengthAwarePaginator
    {
        return Faq::query()
            ->with('author')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('question', 'like', '%'.$search.'%');
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(isset($filters['is_featured']) && $filters['is_featured'] !== '', function ($query) use ($filters) {
                $query->where('is_featured', $filters['is_featured']);
            })
            ->when($filters['category'] ?? null, function ($query, $category) {
                $query->where('category', $category);
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get paginated FAQs for public side.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Faq>
     */
    public function getPaginatedForPublic(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Faq::query()
            ->published()
            ->ordered();

        if (! empty($filters['search'])) {
            $query->search((string) $filters['search']);
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): Faq
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = auth()->id();

            return Faq::create($data);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(string $ulid, array $data): Faq
    {
        return DB::transaction(function () use ($ulid, $data) {
            $faq = Faq::where('ulid', $ulid)->firstOrFail();
            $faq->update($data);

            return $faq;
        });
    }

    public function delete(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $faq = Faq::where('ulid', $ulid)->firstOrFail();

            return $faq->delete();
        });
    }

    public function restore(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $faq = Faq::withTrashed()->where('ulid', $ulid)->firstOrFail();

            return $faq->restore();
        });
    }

    public function forceDelete(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $faq = Faq::withTrashed()->where('ulid', $ulid)->firstOrFail();

            return $faq->forceDelete();
        });
    }

    public function incrementViewCount(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $faq = Faq::where('ulid', $ulid)->firstOrFail();
            $faq->increment('view_count');

            return true;
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PublicationStatus;
use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Session;

final class DocumentService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Get paginated documents for admin panel.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getAdminPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = Document::query()->withDetails();

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['is_pinned'])) {
            $query->where('is_pinned', filter_var($filters['is_pinned'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query->latest('id')->paginate(10)->withQueryString();
    }

    /**
     * Get paginated documents for public view.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getPublicPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = Document::query()
            ->withDetails()
            ->published();

        $search = $filters['search'] ?? $filters['cari'] ?? null;
        if (! empty($search)) {
            $query->search((string) $search);
        }

        $category = $filters['category_id'] ?? $filters['kategori'] ?? $filters['category'] ?? null;
        if (! empty($category)) {
            $query->where(function ($q) use ($category) {
                $q->where('category_id', $category)
                    ->orWhereHas('category', function ($catQuery) use ($category) {
                        $catQuery->where('slug', $category)
                            ->orWhere('name', $category);
                    });
            });
        }

        return $query->latest('is_pinned')
            ->latest('published_at')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();
    }

    /**
     * Create a new document.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, User $user): Document
    {
        return $this->db->transaction(function () use ($data, $user) {
            $document = clone new Document;

            $file = $data['file'];
            $path = $this->mediaService->store($file, 'documents');

            $status = $data['status'] ?? 'draft';
            $statusValue = $status instanceof PublicationStatus ? $status->value : $status;

            $publishedAt = $data['published_at'] ?? null;
            if ($statusValue === 'published' && empty($publishedAt)) {
                $publishedAt = now();
            } elseif ($statusValue === 'draft') {
                $publishedAt = null;
            }

            $document->fill([
                'title' => $data['title'],
                'category_id' => $data['category_id'],
                'status' => $status,
                'published_at' => $publishedAt,
                'is_pinned' => $data['is_pinned'] ?? false,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'disk' => 'public',
                'user_id' => $user->id,
            ]);

            $document->save();

            return $document;
        });
    }

    /**
     * Update an existing document metadata or replace file.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Document $document, array $data): Document
    {
        return $this->db->transaction(function () use ($document, $data) {
            $status = $data['status'] ?? $document->status;
            $statusValue = $status instanceof PublicationStatus ? $status->value : $status;

            $publishedAt = $data['published_at'] ?? $document->published_at;
            if ($statusValue === 'published' && empty($publishedAt)) {
                $publishedAt = now();
            } elseif ($statusValue === 'draft') {
                $publishedAt = null;
            }

            $document->fill([
                'title' => $data['title'],
                'category_id' => $data['category_id'],
                'status' => $status,
                'published_at' => $publishedAt,
                'is_pinned' => $data['is_pinned'] ?? false,
            ]);

            if (isset($data['file'])) {
                $file = $data['file'];
                $path = $this->mediaService->replace($file, $document->file_path, 'documents');

                $document->fill([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }

            $document->save();

            return $document;
        });
    }

    /**
     * Soft delete a document.
     */
    public function delete(Document $document): void
    {
        $this->db->transaction(function () use ($document) {
            $document->delete();
        });
    }

    /**
     * Restore a soft deleted document.
     */
    public function restore(Document $document): void
    {
        $this->db->transaction(function () use ($document) {
            $document->restore();
        });
    }

    /**
     * Force delete a document.
     */
    public function forceDelete(Document $document): void
    {
        $this->db->transaction(function () use ($document) {
            $this->mediaService->delete($document->file_path);
            $document->forceDelete();
        });
    }

    /**
     * Increment download count safely.
     */
    public function incrementDownloadCounter(Document $document): void
    {
        $sessionKey = 'downloaded_document_'.$document->id;

        if (! Session::has($sessionKey)) {
            $document->increment('download_count');
            Session::put($sessionKey, true);
        }
    }
}

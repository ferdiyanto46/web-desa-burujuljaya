<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Mews\Purifier\Facades\Purifier;

final class ArticleService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Store a new article (as draft or published).
     *
     * @param  array<string, mixed>  $data
     */
    public function store(array $data, ?UploadedFile $coverImage = null): Article
    {
        return $this->db->transaction(function () use ($data, $coverImage) {
            $article = clone new Article;

            $data['content'] = Purifier::clean($data['content'] ?? '');

            if ($coverImage) {
                $data['featured_image_path'] = $this->mediaService->store($coverImage, 'articles');
            }

            if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            } elseif (($data['status'] ?? 'draft') === 'draft') {
                $data['published_at'] = null;
            }

            $article->fill($data);
            $article->save();

            return $article;
        });
    }

    /**
     * Update an existing article.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Article $article, array $data, ?UploadedFile $coverImage = null): Article
    {
        return $this->db->transaction(function () use ($article, $data, $coverImage) {
            if (isset($data['content']) && is_string($data['content'])) {
                $data['content'] = Purifier::clean($data['content']);
            }

            if ($coverImage) {
                $data['featured_image_path'] = $this->mediaService->replace(
                    $coverImage,
                    $article->featured_image_path,
                    'articles'
                );
            }

            $newStatus = $data['status'] ?? $article->status->value;
            if ($newStatus === 'published') {
                if (empty($data['published_at'])) {
                    $data['published_at'] = $article->published_at ?? now();
                }
            } elseif ($newStatus === 'draft') {
                $data['published_at'] = null;
            }

            $article->fill($data);
            $article->save();

            return $article;
        });
    }

    /**
     * Soft delete an article.
     */
    public function delete(Article $article): void
    {
        $article->delete();
    }

    /**
     * Restore a soft-deleted article.
     */
    public function restore(Article $article): void
    {
        $article->restore();
    }

    /**
     * Permanently delete an article and its media.
     */
    public function forceDelete(Article $article): void
    {
        if ($article->featured_image_path) {
            $this->mediaService->delete($article->featured_image_path);
        }

        $article->forceDelete();
    }
}

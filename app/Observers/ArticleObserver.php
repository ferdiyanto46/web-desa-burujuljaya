<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Article;
use App\Services\ActivityLogService;

final class ArticleObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Article $article): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Membuat artikel: {$article->title}",
            subject: $article,
            logName: 'article',
        );
    }

    public function updated(Article $article): void
    {

        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui artikel: {$article->title}",
            subject: $article,
            properties: [
                'old' => $article->getOriginal(),
                'attributes' => $article->getChanges(),
            ],
            logName: 'article',
        );
    }

    public function deleted(Article $article): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) artikel: {$article->title}",
            subject: $article,
            logName: 'article',
        );
    }

    public function forceDeleted(Article $article): void
    {

        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen artikel: {$article->title}",
            subject: $article,
            logName: 'article',
        );
    }
}

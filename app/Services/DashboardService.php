<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\Complaint;
use App\Models\ContactInformation;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Tourism;
use App\Models\Umkm;
use App\Models\VillagePage;
use App\Models\VillageService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class DashboardService
{
    /**
     * Get all dashboard statistics with 5 minutes cache.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        return Cache::remember('admin.dashboard.statistics', 300, function () {
            $totalArticles = Article::count();
            $publishedArticles = Article::where('status', 'published')->count();
            $draftArticles = $totalArticles - $publishedArticles;

            $totalAnnouncements = Announcement::count();
            $publishedAnnouncements = Announcement::where('status', 'published')->count();
            $draftAnnouncements = $totalAnnouncements - $publishedAnnouncements;

            return [
                'total_articles' => $totalArticles,
                'total_announcements' => $totalAnnouncements,
                'total_gallery_items' => Gallery::count(),
                'total_tourism' => Tourism::count(),
                'total_umkm' => Umkm::count(),
                'total_products' => Product::count(),
                'total_village_services' => VillageService::count(),
                'total_faq' => Faq::count(),
                'total_contact_entries' => ContactInformation::count(),
                'total_village_pages' => VillagePage::count(),
                'articles_published' => $publishedArticles,
                'articles_draft' => $draftArticles,
                'announcements_published' => $publishedAnnouncements,
                'announcements_draft' => $draftAnnouncements,
            ];
        });
    }

    public function getMostViewedContent(int $limit = 5)
    {
        return Article::with('author:id,name')
            ->where('status', 'published')
            ->orderByDesc('views')
            ->limit($limit)
            ->get(['id', 'title', 'user_id', 'status', 'published_at', 'views']);
    }

    /**
     * Get recent articles.
     *
     * @return Collection<int, Article>
     */
    public function getRecentArticles(int $limit = 5)
    {
        return Article::with('author:id,name')
            ->latest()
            ->limit($limit)
            ->get(['id', 'title', 'user_id', 'status', 'published_at', 'views']);
    }

    /**
     * Get recent complaints.
     *
     * @return Collection<int, Complaint>
     */
    public function getRecentComplaints(int $limit = 5)
    {
        return Complaint::latest()
            ->limit($limit)
            ->get(['id', 'ulid', 'title', 'status', 'created_at']);
    }

    /**
     * Get recent activities from the ActivityLog.
     *
     * @return Collection<int, ActivityLog>
     */
    public function getRecentActivities(int $limit = 10)
    {
        return ActivityLog::with('causer:id,name')
            ->latest()
            ->limit($limit)
            ->get();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\Product;
use App\Models\Tourism;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $statistics = [
            'articles' => Article::count(),
            'announcements' => Announcement::count(),
            'agendas' => Agenda::count(),
            'documents' => Document::count(),
            'complaints' => Complaint::count(),
            'products' => Product::count(),
            'umkms' => Umkm::count(),
            'tourisms' => Tourism::count(),
        ];

        $recentArticles = Article::with('user:id,name')
            ->latest()
            ->limit(5)
            ->get(['id', 'title', 'user_id', 'status', 'published_at']);

        $recentComplaints = Complaint::latest()
            ->limit(5)
            ->get(['id', 'tracking_code', 'title', 'status', 'created_at']);

        $recentActivities = ActivityLog::with('user:id,name')
            ->latest()
            ->limit(5)
            ->get();

        $systemStatus = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'database' => config('database.default'),
            'storage' => config('filesystems.default'),
        ];

        return Inertia::render('Admin/Dashboard/Index', [
            'statistics' => $statistics,
            'recentArticles' => $recentArticles,
            'recentComplaints' => $recentComplaints,
            'recentActivities' => $recentActivities,
            'systemStatus' => $systemStatus,
        ]);
    }
}

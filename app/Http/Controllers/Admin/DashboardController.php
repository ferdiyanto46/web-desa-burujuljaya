<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $systemStatus = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'database' => config('database.default'),
            'storage' => config('filesystems.default'),
        ];

        return Inertia::render('Admin/Dashboard/Index', [
            'statistics' => $this->dashboardService->getStatistics(),
            'recentArticles' => $this->dashboardService->getRecentArticles(),
            'recentComplaints' => $this->dashboardService->getRecentComplaints(),
            'recentActivities' => $this->dashboardService->getRecentActivities(),
            'systemStatus' => $systemStatus,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\CacheService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class CacheController extends Controller
{
    public function __construct(
        private readonly CacheService $cacheService,
        private readonly ActivityLogService $activityLogService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('manage settings');

        return Inertia::render('Admin/Cache/Index');
    }

    /**
     * Clear the specified cache.
     */
    public function clear(Request $request): RedirectResponse
    {
        Gate::authorize('manage settings');

        $type = $request->input('type');

        try {
            switch ($type) {
                case 'application':
                    $this->cacheService->clearApplicationCache();
                    $message = 'Application cache cleared successfully.';
                    break;
                case 'views':
                    $this->cacheService->clearViewsCache();
                    $message = 'Views cache cleared successfully.';
                    break;
                case 'routes':
                    $this->cacheService->clearRoutesCache();
                    $message = 'Routes cache cleared successfully.';
                    break;
                case 'config':
                    $this->cacheService->clearConfigCache();
                    $message = 'Config cache cleared successfully.';
                    break;
                case 'all':
                    $this->cacheService->clearAll();
                    $message = 'All caches cleared successfully.';
                    break;
                default:
                    return back()->with('error', 'Invalid cache type.');
            }

            $this->activityLogService->log(
                event: 'cleared',
                description: "Cleared {$type} cache",
            );

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: '.$e->getMessage());
        }
    }
}

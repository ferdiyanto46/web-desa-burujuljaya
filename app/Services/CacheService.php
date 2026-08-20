<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

final class CacheService
{
    /**
     * Clear application cache.
     */
    public function clearApplicationCache(): void
    {
        Artisan::call('cache:clear');
    }

    /**
     * Clear views cache.
     */
    public function clearViewsCache(): void
    {
        Artisan::call('view:clear');
    }

    /**
     * Clear routes cache.
     */
    public function clearRoutesCache(): void
    {
        Artisan::call('route:clear');
    }

    /**
     * Clear config cache.
     */
    public function clearConfigCache(): void
    {
        Artisan::call('config:clear');
    }

    /**
     * Clear all caches.
     */
    public function clearAll(): void
    {
        Artisan::call('optimize:clear');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use App\Models\Tourism;
use App\Models\VillagePage;
use App\Models\VillageService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

final class SitemapController extends Controller
{
    /**
     * Generate the sitemap XML dynamically with 1 hour cache.
     */
    public function __invoke(): Response
    {
        $xml = Cache::remember('sitemap_xml', 3600, function () {
            $urls = $this->getUrls();

            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            foreach ($urls as $url) {
                $content .= '<url>';
                $content .= '<loc>'.htmlspecialchars($url['loc']).'</loc>';
                if (isset($url['lastmod'])) {
                    $content .= '<lastmod>'.$url['lastmod'].'</lastmod>';
                }
                if (isset($url['changefreq'])) {
                    $content .= '<changefreq>'.$url['changefreq'].'</changefreq>';
                }
                if (isset($url['priority'])) {
                    $content .= '<priority>'.$url['priority'].'</priority>';
                }
                $content .= '</url>';
            }

            $content .= '</urlset>';

            return $content;
        });

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function getUrls(): array
    {
        $urls = [];
        $now = now()->toAtomString();

        // Static routes
        $staticRoutes = [
            'home' => ['changefreq' => 'daily', 'priority' => '1.0'],
            'profile' => ['changefreq' => 'monthly', 'priority' => '0.8'],
            'articles.index' => ['changefreq' => 'daily', 'priority' => '0.9'],
            'products.index' => ['changefreq' => 'daily', 'priority' => '0.9'],
            'tourisms.index' => ['changefreq' => 'weekly', 'priority' => '0.9'],
            'services.index' => ['changefreq' => 'monthly', 'priority' => '0.8'],
            'contact.index' => ['changefreq' => 'monthly', 'priority' => '0.5'],
            'faq.index' => ['changefreq' => 'monthly', 'priority' => '0.5'],
        ];

        foreach ($staticRoutes as $route => $config) {
            $urls[] = [
                'loc' => route($route),
                'lastmod' => $now,
                'changefreq' => $config['changefreq'],
                'priority' => $config['priority'],
            ];
        }

        // Dynamic Routes - Village Pages
        VillagePage::published()->get(['slug', 'updated_at'])->each(function ($page) use (&$urls) {
            $urls[] = [
                'loc' => route('pages.show', $page->slug),
                'lastmod' => $page->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        });

        // Dynamic Routes - Articles
        Article::published()->get(['slug', 'updated_at'])->each(function ($article) use (&$urls) {
            $urls[] = [
                'loc' => route('articles.show', $article->slug),
                'lastmod' => $article->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        });

        // Dynamic Routes - Products
        Product::published()->get(['slug', 'updated_at'])->each(function ($product) use (&$urls) {
            $urls[] = [
                'loc' => route('products.show', $product->slug),
                'lastmod' => $product->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        });

        // Dynamic Routes - Tourism
        Tourism::published()->get(['slug', 'updated_at'])->each(function ($tourism) use (&$urls) {
            $urls[] = [
                'loc' => route('tourisms.show', $tourism->slug),
                'lastmod' => $tourism->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        });

        // Dynamic Routes - Services
        VillageService::published()->get(['slug', 'updated_at'])->each(function ($service) use (&$urls) {
            $urls[] = [
                'loc' => route('services.show', $service->slug),
                'lastmod' => $service->updated_at->toAtomString(),
                'changefreq' => 'yearly',
                'priority' => '0.7',
            ];
        });

        return $urls;
    }
}

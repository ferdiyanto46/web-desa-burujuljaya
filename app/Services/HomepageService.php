<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Announcement;
use App\Models\Apparatus;
use App\Models\Article;
use App\Models\ContactInformation;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Tourism;
use App\Models\Umkm;
use App\Models\VillageService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class HomepageService
{
    public function __construct(
        private readonly VillageProfileService $villageProfileService,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Get sections for admin.
     *
     * @return Collection<int, HomepageSection>
     */
    public function getSectionsForAdmin()
    {
        return HomepageSection::ordered()->get();
    }

    /**
     * Get public homepage sections with aggregated data.
     *
     * @return Collection<int, HomepageSection>
     */
    public function getPublicHomepage()
    {
        $sections = HomepageSection::enabled()->ordered()->get();

        foreach ($sections as $section) {
            $section->aggregated_data = $this->aggregateDataForSection($section);
        }

        return $sections;
    }

    /**
     * Aggregate data based on section key.
     *
     * @return mixed
     */
    private function aggregateDataForSection(HomepageSection $section)
    {
        $limit = $section->configuration['limit'] ?? 6;

        switch ($section->section_key) {
            case 'hero_slider':
                $slides = $section->configuration['slides'] ?? [];
                $bannerUrl = $section->configuration['banner_image_url'] ?? null;
                if (! empty($section->configuration['banner_image_path'])) {
                    $bannerUrl = '/storage/'.ltrim($section->configuration['banner_image_path'], '/');
                }

                if (empty($slides)) {
                    $profile = $this->villageProfileService->getActive();

                    return [
                        [
                            'title' => $section->title ?: ($profile ? "Selamat Datang di Desa {$profile->village_name}" : 'Selamat Datang di Desa Burujuljaya'),
                            'subtitle' => $section->subtitle ?: ($profile?->vision ?? 'Mewujudkan desa yang maju, mandiri, transparan, dan sejahtera.'),
                            'image_url' => $bannerUrl ?: '/images/hero-bg.jpg',
                        ],
                    ];
                }

                if ($bannerUrl) {
                    foreach ($slides as &$slide) {
                        if (empty($slide['image_url'])) {
                            $slide['image_url'] = $bannerUrl;
                        }
                    }
                }

                return $slides;

            case 'welcome_message':
                return $this->villageProfileService->getActive();

            case 'featured_articles':
                return Article::published()->latest('published_at')->take($limit)->get();

            case 'announcements':
                return Announcement::active()->latest()->take($limit)->get();

            case 'gallery':
                return Gallery::published()->with(['photos' => function ($q) {
                    $q->take(4);
                }])->latest('published_at')->take($limit)->get();

            case 'tourism':
                return Tourism::published()->latest('published_at')->take($limit)->get();

            case 'umkm':
                return Umkm::published()->latest('published_at')->take($limit)->get();

            case 'village_products':
                return Product::published()->latest('published_at')->take($limit)->get();

            case 'village_services':
                return VillageService::published()->latest('published_at')->take($limit)->get();

            case 'apparatus':
                return Apparatus::active()->with('position')->ordered()->take($limit)->get();

            case 'faq':
                return Faq::published()->ordered()->latest('published_at')->take($limit)->get();

            case 'contact_preview':
                $contacts = ContactInformation::published()->ordered()->latest('published_at')->take($limit)->get();
                if ($contacts->isEmpty()) {
                    return $this->villageProfileService->getActive();
                }

                return $contacts;

            case 'statistics':
                $stats = $section->configuration['stats'] ?? [];
                if (empty($stats)) {
                    $profile = $this->villageProfileService->getActive();

                    return [
                        'population' => '3.420',
                        'families' => '1.045',
                        'area' => $profile?->area_hectares ?? '14.5',
                        'rt_rw' => '18 / 06',
                    ];
                }

                return $stats;

            case 'map':
                $profile = $this->villageProfileService->getActive();

                return [
                    'village_name' => $profile?->village_name ?? 'Burujuljaya',
                    'address' => $profile?->address ?? '',
                    'latitude' => $section->configuration['latitude'] ?? $profile?->latitude ?? '-6.8362145',
                    'longitude' => $section->configuration['longitude'] ?? $profile?->longitude ?? '108.2287412',
                    'embed_url' => $section->configuration['embed_url'] ?? null,
                    'phone' => $profile?->phone,
                    'email' => $profile?->email,
                ];

            default:
                return null;
        }
    }

    /**
     * Update section configuration.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateSection(HomepageSection $section, array $data, ?UploadedFile $bannerImage = null): HomepageSection
    {
        return DB::transaction(function () use ($section, $data, $bannerImage) {
            $config = $data['configuration'] ?? $section->configuration ?? [];

            if ($bannerImage) {
                $currentPath = $section->configuration['banner_image_path'] ?? null;
                $path = $this->mediaService->replace($bannerImage, $currentPath, 'homepage');
                if ($path) {
                    $config['banner_image_path'] = $path;
                    $config['banner_image_url'] = '/storage/'.ltrim($path, '/');
                }
            }

            if (! empty($config['embed_url']) && is_string($config['embed_url']) && preg_match('/src=["\']([^"\']+)["\']/', $config['embed_url'], $matches)) {
                $config['embed_url'] = $matches[1];
            }

            $data['configuration'] = $config;

            $section->update($data);
            Cache::forget('homepage_sections');

            return $section;
        });
    }

    /**
     * Toggle section enabled state.
     */
    public function toggleSection(HomepageSection $section): HomepageSection
    {
        return DB::transaction(function () use ($section) {
            $section->update(['is_enabled' => ! $section->is_enabled]);
            Cache::forget('homepage_sections');

            return $section;
        });
    }

    /**
     * Reorder sections.
     *
     * @param  array<string, int>  $orderData  (ulid => sort_order)
     */
    public function reorderSections(array $orderData): void
    {
        DB::transaction(function () use ($orderData) {
            foreach ($orderData as $ulid => $sortOrder) {
                HomepageSection::where('ulid', $ulid)->update(['sort_order' => $sortOrder]);
            }
            Cache::forget('homepage_sections');
        });
    }
}

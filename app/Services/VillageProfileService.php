<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VillageProfile;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class VillageProfileService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly CacheRepository $cache
    ) {}

    private const CACHE_KEY = 'village_profile.active';

    /**
     * Get the active village profile with its missions.
     */
    public function getActive(): ?VillageProfile
    {
        $cached = $this->cache->get(self::CACHE_KEY);

        if ($cached instanceof VillageProfile) {
            return $cached;
        }

        // Invalidate invalid/corrupted cache and fetch freshly from database
        $this->flushCache();

        $profile = VillageProfile::with('missions')->first();

        if ($profile instanceof VillageProfile) {
            $this->cache->forever(self::CACHE_KEY, $profile);
        }

        return $profile;
    }

    /**
     * Update the village profile, handle logo upload, and invalidate cache.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(array $data, ?UploadedFile $logo = null): VillageProfile
    {
        return $this->db->transaction(function () use ($data, $logo) {
            $profile = VillageProfile::firstOrCreate([]);

            unset($data['logo']);

            if ($logo instanceof UploadedFile && $logo->isValid()) {
                $oldLogo = $profile->logo_path;

                $path = $logo->store('village', 'public');
                if (is_string($path)) {
                    $data['logo_path'] = $path;

                    if (is_string($oldLogo) && $oldLogo !== $path && Storage::disk('public')->exists($oldLogo)) {
                        Storage::disk('public')->delete($oldLogo);
                    }
                }
            }

            if (array_key_exists('missions', $data)) {
                $missions = $data['missions'];
                unset($data['missions']);

                if (is_array($missions)) {
                    $profile->missions()->delete();
                    $order = 10;
                    foreach ($missions as $item) {
                        $content = is_array($item) ? ($item['content'] ?? $item['statement'] ?? '') : (is_string($item) ? $item : '');
                        $content = trim((string) $content);
                        if ($content !== '') {
                            $profile->missions()->create([
                                'content' => $content,
                                'sort_order' => is_array($item) && isset($item['sort_order']) ? (int) $item['sort_order'] : $order,
                            ]);
                            $order += 10;
                        }
                    }
                }
            }

            $profile->update($data);

            $this->flushCache();

            return $profile->load('missions');
        });
    }

    /**
     * Flush village profile cache.
     */
    public function flushCache(): void
    {
        $this->cache->forget(self::CACHE_KEY);
    }
}

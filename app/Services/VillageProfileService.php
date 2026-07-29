<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VillageProfile;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;

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
        return $this->cache->rememberForever(self::CACHE_KEY, function () {
            return VillageProfile::with('missions')->first();
        });
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

            if ($logo) {
                // Storage cleanup of the old logo will be handled by the Observer
                $path = $logo->store('village', 'public');
                if (is_string($path)) {
                    $data['logo_path'] = $path;
                }
            }

            $profile->update($data);

            $this->flushCache();

            return $profile;
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

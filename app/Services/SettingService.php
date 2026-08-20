<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Database\Eloquent\Collection;

final class SettingService
{
    public function __construct(
        private readonly CacheRepository $cache,
        private readonly Encrypter $crypt
    ) {}

    private const CACHE_KEY = 'settings.all';

    private const PUBLIC_CACHE_KEY = 'settings.public';

    /**
     * Get all cached settings.
     *
     * @return Collection<int, Setting>
     */
    public function getAll(): Collection
    {
        return Setting::all();
    }

    /**
     * Get a specific setting value, automatically decrypting and casting.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->getAll();
        $setting = $settings->where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        $value = $setting->value;

        if ($setting->is_encrypted && $value !== null) {
            $value = $this->crypt->decryptString($value);
        }

        return $this->castValue($value, $setting->type);
    }

    /**
     * Update a setting value and invalidate cache.
     */
    public function update(string $key, mixed $value): Setting
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        if ($setting->is_encrypted && $value !== null) {
            $value = $this->crypt->encryptString((string) $value);
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $value = json_encode($value);
        }

        $setting->update(['value' => $value === null ? null : (string) $value]);

        $this->flushCache();

        return $setting;
    }

    /**
     * Get public settings formatted as a key-value array.
     *
     * @return array<string, mixed>
     */
    public function forPublic(): array
    {
        $settings = Setting::public()->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = $this->castValue($setting->value, $setting->type);
        }

        return $result;
    }

    /**
     * Flush all setting caches.
     */
    public function flushCache(): void
    {
        $this->cache->forget(self::CACHE_KEY);
        $this->cache->forget(self::PUBLIC_CACHE_KEY);
    }

    /**
     * Cast raw string value to the appropriate PHP type.
     */
    private function castValue(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}

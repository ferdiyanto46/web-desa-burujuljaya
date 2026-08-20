<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\VillageProfileObserver;
use App\Services\VillageProfileService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Represents the official institutional profile of Desa Burujuljaya.
 *
 * Singleton: only one row will exist in the database.
 * Always retrieved via: VillageProfile::first() or VillageProfile::sole()
 *
 * No soft delete: profile is permanent institutional data — updated, not deleted.
 * No UUID/ULID: a singleton has no public-facing enumerable identifier.
 * No factory: singleton data is managed via VillageProfileSeeder and admin panel.
 *
 * Cache: this model is intended to be cached via VillageProfileService.
 *        Use VillageProfileService::get() in application code, not VillageProfile::first()
 *        directly, to benefit from cache.
 *
 * Security: logo_path is never populated from user-submitted form data directly.
 *           It is always set by VillageProfileService::uploadLogo() which generates
 *           a ULID-based filename from a validated MIME type.
 *
 * @property int $id
 * @property string $village_name
 * @property string|null $village_code
 * @property string $district
 * @property string $regency
 * @property string $province
 * @property string|null $postal_code
 * @property string $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website_url
 * @property string|null $history
 * @property string|null $vision
 * @property string|null $area_hectares Stored as string due to decimal:2 cast
 * @property string|null $latitude Stored as string due to decimal:7 cast
 * @property string|null $longitude Stored as string due to decimal:7 cast
 * @property int|null $established_year
 * @property string|null $logo_path
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, VillageMission> $missions
 * @property-read int|null $missions_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereAreaHectares($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereEstablishedYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereRegency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereVillageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereVillageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VillageProfile whereWebsiteUrl($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(VillageProfileObserver::class)]
final class VillageProfile extends Model
{
    /**
     * Attributes that may be set via fill() or create().
     *
     * logo_path is included in fillable because VillageProfileService (trusted code)
     * uses fill() to update the path after a validated upload.
     * Controllers must never accept logo_path directly from request input.
     *
     * @var list<string>
     */
    protected $fillable = [
        'village_name',
        'village_code',
        'district',
        'regency',
        'province',
        'postal_code',
        'address',
        'phone',
        'email',
        'website_url',
        'history',
        'vision',
        'area_hectares',
        'latitude',
        'longitude',
        'established_year',
        'logo_path',
    ];

    /**
     * Attribute type casts.
     *
     * Decimal values use the 'decimal:N' cast which returns a string representation
     * (not a float) to preserve precision and avoid IEEE 754 floating-point errors.
     * This is intentional — GPS coordinates and hectares should never be arithmetic-rounded.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'area_hectares' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'established_year' => 'integer',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * The village's ordered mission statements.
     *
     * Ordered by sort_order ASC at the query level so that callers never need
     * to remember to order manually. The order is always deterministic.
     *
     * @return HasMany<VillageMission, $this>
     */
    public function missions(): HasMany
    {
        return $this->hasMany(VillageMission::class)->orderBy('sort_order');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Accessors & Helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Return the fully-qualified public URL for the village logo.
     *
     * Returns null when no logo has been uploaded, letting the frontend
     * render a text-based fallback (e.g., village name initials).
     *
     * Uses Storage::disk('public') consistently — never constructs raw paths.
     */
    public function logoUrl(): ?string
    {
        if (! $this->logo_path) {
            return null;
        }

        if (config('filesystems.disks.public.driver') === 'local') {
            if (! Storage::disk('public')->exists($this->logo_path)) {
                return null;
            }

            return '/storage/'.ltrim($this->logo_path, '/');
        }

        return Storage::disk('public')->url($this->logo_path);
    }

    /**
     * Return a formatted administrative area string for display purposes.
     *
     * Example output: "Kec. Manonjaya, Kab. Tasikmalaya, Jawa Barat"
     * Handles nullable district gracefully.
     */
    public function fullAdministrativeArea(): string
    {
        return implode(', ', array_filter([
            $this->district !== null ? "Kec. {$this->district}" : null,
            $this->regency,
            $this->province,
        ]));
    }

    /**
     * Determine whether GPS coordinates have been configured.
     *
     * Used by the frontend to conditionally render the Google Maps embed.
     */
    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Get the active village profile singleton instance.
     */
    public static function getActive(): ?self
    {
        return app(VillageProfileService::class)->getActive();
    }
}

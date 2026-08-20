<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $ulid
 * @property int $category_id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $description
 * @property string|null $featured_image_path
 * @property string|null $address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $google_maps_url
 * @property string|null $opening_hours
 * @property string|null $ticket_price
 * @property string|null $contact_person
 * @property string|null $phone
 * @property string|null $website
 * @property PublicationStatus $status
 * @property CarbonImmutable|null $published_at
 * @property bool $is_featured
 * @property int $featured_order
 * @property bool $is_pinned
 * @property int $view_count
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_image
 * @property string|null $canonical_url
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $author
 * @property-read TourismCategory|null $category
 * @property-read Collection<int, TourismFacility> $facilities
 * @property-read int|null $facilities_count
 * @property-read string|null $featured_image_url
 * @property-read Collection<int, TourismPhoto> $photos
 * @property-read int|null $photos_count
 *
 * @method static \Database\Factories\TourismFactory factory($count = null, $state = [])
 * @method static Builder<static>|Tourism featured()
 * @method static Builder<static>|Tourism latest()
 * @method static Builder<static>|Tourism nearby(float $lat, float $lng, float $radiusKm = 10)
 * @method static Builder<static>|Tourism newModelQuery()
 * @method static Builder<static>|Tourism newQuery()
 * @method static Builder<static>|Tourism onlyTrashed()
 * @method static Builder<static>|Tourism popular()
 * @method static Builder<static>|Tourism published()
 * @method static Builder<static>|Tourism query()
 * @method static Builder<static>|Tourism search(string $search)
 * @method static Builder<static>|Tourism whereAddress($value)
 * @method static Builder<static>|Tourism whereCanonicalUrl($value)
 * @method static Builder<static>|Tourism whereCategoryId($value)
 * @method static Builder<static>|Tourism whereContactPerson($value)
 * @method static Builder<static>|Tourism whereCreatedAt($value)
 * @method static Builder<static>|Tourism whereDeletedAt($value)
 * @method static Builder<static>|Tourism whereDescription($value)
 * @method static Builder<static>|Tourism whereExcerpt($value)
 * @method static Builder<static>|Tourism whereFeaturedImagePath($value)
 * @method static Builder<static>|Tourism whereFeaturedOrder($value)
 * @method static Builder<static>|Tourism whereGoogleMapsUrl($value)
 * @method static Builder<static>|Tourism whereId($value)
 * @method static Builder<static>|Tourism whereIsFeatured($value)
 * @method static Builder<static>|Tourism whereIsPinned($value)
 * @method static Builder<static>|Tourism whereLatitude($value)
 * @method static Builder<static>|Tourism whereLongitude($value)
 * @method static Builder<static>|Tourism whereMetaDescription($value)
 * @method static Builder<static>|Tourism whereMetaTitle($value)
 * @method static Builder<static>|Tourism whereOgImage($value)
 * @method static Builder<static>|Tourism whereOpeningHours($value)
 * @method static Builder<static>|Tourism wherePhone($value)
 * @method static Builder<static>|Tourism wherePublishedAt($value)
 * @method static Builder<static>|Tourism whereSlug($value)
 * @method static Builder<static>|Tourism whereStatus($value)
 * @method static Builder<static>|Tourism whereTicketPrice($value)
 * @method static Builder<static>|Tourism whereTitle($value)
 * @method static Builder<static>|Tourism whereUlid($value)
 * @method static Builder<static>|Tourism whereUpdatedAt($value)
 * @method static Builder<static>|Tourism whereUserId($value)
 * @method static Builder<static>|Tourism whereViewCount($value)
 * @method static Builder<static>|Tourism whereWebsite($value)
 * @method static Builder<static>|Tourism withDetails()
 * @method static Builder<static>|Tourism withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Tourism withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Tourism extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $table = 'tourisms';

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'featured_image_path',
        'address',
        'latitude',
        'longitude',
        'google_maps_url',
        'opening_hours',
        'ticket_price',
        'contact_person',
        'phone',
        'website',
        'status',
        'published_at',
        'is_featured',
        'featured_order',
        'is_pinned',
        'view_count',
        'meta_title',
        'meta_description',
        'og_image',
        'canonical_url',
    ];

    protected $casts = [
        'status' => PublicationStatus::class,
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Tourism $tourism) {
            // Auto-generate Google Maps URL if empty but coordinates exist
            if (empty($tourism->google_maps_url) && ! empty($tourism->latitude) && ! empty($tourism->longitude)) {
                $tourism->google_maps_url = sprintf('https://maps.google.com/?q=%s,%s', $tourism->latitude, $tourism->longitude);
            }
        });
    }

    /**
     * Get the category that owns the tourism destination.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TourismCategory::class, 'category_id');
    }

    /**
     * Get the author that created the tourism destination.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the facilities associated with the tourism destination.
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(TourismFacility::class, 'tourism_facility_tourism', 'tourism_id', 'tourism_facility_id');
    }

    /**
     * Get the photos for the tourism destination.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(TourismPhoto::class)->orderBy('order');
    }

    /**
     * Get the full URL for the featured image.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image_path ? Storage::disk('public')->url($this->featured_image_path) : null;
    }

    /**
     * Scope a query to include necessary relations.
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with(['category', 'author', 'facilities', 'photos']);
    }

    /**
     * Scope a query to only include published destinations.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include featured destinations.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)->orderBy('featured_order');
    }

    /**
     * Scope a query to order by latest published.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('is_pinned')->orderByDesc('published_at');
    }

    /**
     * Scope a query to order by most popular (view_count).
     */
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('view_count')->orderByDesc('published_at');
    }

    /**
     * Scope a query to search destinations.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to find nearby destinations (naive bounding box if coordinates exist).
     */
    public function scopeNearby(Builder $query, float $lat, float $lng, float $radiusKm = 10): Builder
    {
        // A simple bounding box approach for nearby search in Eloquent without raw Haversine SQL for simplicity and database portability
        // 1 degree of latitude is ~111 km.
        // 1 degree of longitude is ~111 * cos(lat) km.
        $latDelta = $radiusKm / 111.0;
        $lngDelta = $radiusKm / (111.0 * cos(deg2rad($lat)));

        return $query->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
            ->whereBetween('longitude', [$lng - $lngDelta, $lng + $lngDelta]);
    }
}

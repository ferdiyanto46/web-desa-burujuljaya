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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property PublicationStatus $status
 * @property string $ulid
 * @property string $slug
 * @property string|null $google_maps_url
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $featured_image_path
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $published_at
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $owner_name
 * @property string|null $phone
 * @property string|null $address
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property string|null $whatsapp
 * @property string|null $email
 * @property string|null $website
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $operating_hours
 * @property bool $is_featured
 * @property bool $is_pinned
 * @property int $view_count
 * @property int $featured_order
 * @property-read UmkmCategory $category
 * @property-read Collection<int, UmkmPhoto> $photos
 * @property-read int|null $photos_count
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
 * @property-read User|null $user
 *
 * @method static \Database\Factories\UmkmFactory factory($count = null, $state = [])
 * @method static Builder<static>|Umkm featured()
 * @method static Builder<static>|Umkm latest()
 * @method static Builder<static>|Umkm newModelQuery()
 * @method static Builder<static>|Umkm newQuery()
 * @method static Builder<static>|Umkm onlyTrashed()
 * @method static Builder<static>|Umkm popular()
 * @method static Builder<static>|Umkm published()
 * @method static Builder<static>|Umkm query()
 * @method static Builder<static>|Umkm whereAddress($value)
 * @method static Builder<static>|Umkm whereCategoryId($value)
 * @method static Builder<static>|Umkm whereCreatedAt($value)
 * @method static Builder<static>|Umkm whereDeletedAt($value)
 * @method static Builder<static>|Umkm whereDescription($value)
 * @method static Builder<static>|Umkm whereEmail($value)
 * @method static Builder<static>|Umkm whereFacebook($value)
 * @method static Builder<static>|Umkm whereFeaturedImagePath($value)
 * @method static Builder<static>|Umkm whereFeaturedOrder($value)
 * @method static Builder<static>|Umkm whereGoogleMapsUrl($value)
 * @method static Builder<static>|Umkm whereId($value)
 * @method static Builder<static>|Umkm whereInstagram($value)
 * @method static Builder<static>|Umkm whereIsFeatured($value)
 * @method static Builder<static>|Umkm whereIsPinned($value)
 * @method static Builder<static>|Umkm whereLatitude($value)
 * @method static Builder<static>|Umkm whereLongitude($value)
 * @method static Builder<static>|Umkm whereOperatingHours($value)
 * @method static Builder<static>|Umkm whereOwnerName($value)
 * @method static Builder<static>|Umkm wherePhone($value)
 * @method static Builder<static>|Umkm wherePublishedAt($value)
 * @method static Builder<static>|Umkm whereSlug($value)
 * @method static Builder<static>|Umkm whereStatus($value)
 * @method static Builder<static>|Umkm whereTitle($value)
 * @method static Builder<static>|Umkm whereUlid($value)
 * @method static Builder<static>|Umkm whereUpdatedAt($value)
 * @method static Builder<static>|Umkm whereUserId($value)
 * @method static Builder<static>|Umkm whereViewCount($value)
 * @method static Builder<static>|Umkm whereWebsite($value)
 * @method static Builder<static>|Umkm whereWhatsapp($value)
 * @method static Builder<static>|Umkm withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Umkm withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Umkm extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $table = 'umkms';

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'owner_name',
        'description',
        'featured_image_path',
        'address',
        'latitude',
        'longitude',
        'google_maps_url',
        'phone',
        'whatsapp',
        'email',
        'website',
        'instagram',
        'facebook',
        'operating_hours',
        'status',
        'published_at',
        'is_featured',
        'is_pinned',
        'view_count',
        'featured_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PublicationStatus::class,
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'view_count' => 'integer',
            'featured_order' => 'integer',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (Umkm $umkm) {
            if (empty($umkm->google_maps_url) && $umkm->latitude && $umkm->longitude) {
                $umkm->google_maps_url = "https://www.google.com/maps?q={$umkm->latitude},{$umkm->longitude}";
            }
        });
    }

    /**
     * The slug source field.
     */
    public function slugSource(): string
    {
        return 'title';
    }

    /**
     * Get the category that owns the umkm.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(UmkmCategory::class, 'category_id');
    }

    /**
     * Get the user that owns the umkm.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the products for the umkm.
     */
    public function products(): HasMany
    {
        // Keeping this for backwards compatibility if it exists elsewhere
        return $this->hasMany(Product::class, 'umkm_id');
    }

    /**
     * Get the photos for the umkm.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(UmkmPhoto::class, 'umkm_id')->orderBy('order_column');
    }

    /**
     * Scope a query to only include published items.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', PublicationStatus::Published);
    }

    /**
     * Scope a query to only include featured items.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true)
            ->orderBy('featured_order', 'asc');
    }

    /**
     * Scope a query to order by latest published.
     */
    public function scopeLatest(Builder $query): void
    {
        $query->orderByDesc('published_at');
    }

    /**
     * Scope a query to order by popularity (view count).
     */
    public function scopePopular(Builder $query): void
    {
        $query->orderByDesc('view_count');
    }
}

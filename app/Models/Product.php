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
 * @property string $title
 * @property string|null $excerpt
 * @property string|null $description
 * @property string|null $sku
 * @property int $price
 * @property int $stock
 * @property string|null $unit
 * @property float|null $weight
 * @property string|null $featured_image_path
 * @property Carbon|null $published_at
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int|null $umkm_id
 * @property bool $is_featured
 * @property bool $is_pinned
 * @property int $view_count
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $canonical_url
 * @property-read User|null $author
 * @property-read ProductCategory $category
 * @property-read Collection<int, ProductPhoto> $photos
 * @property-read int|null $photos_count
 * @property-read Umkm|null $umkm
 *
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static Builder<static>|Product featured()
 * @method static Builder<static>|Product newModelQuery()
 * @method static Builder<static>|Product newQuery()
 * @method static Builder<static>|Product onlyTrashed()
 * @method static Builder<static>|Product popular()
 * @method static Builder<static>|Product published()
 * @method static Builder<static>|Product query()
 * @method static Builder<static>|Product search(?string $search)
 * @method static Builder<static>|Product whereCanonicalUrl($value)
 * @method static Builder<static>|Product whereCategoryId($value)
 * @method static Builder<static>|Product whereCreatedAt($value)
 * @method static Builder<static>|Product whereDeletedAt($value)
 * @method static Builder<static>|Product whereDescription($value)
 * @method static Builder<static>|Product whereExcerpt($value)
 * @method static Builder<static>|Product whereFeaturedImagePath($value)
 * @method static Builder<static>|Product whereId($value)
 * @method static Builder<static>|Product whereIsFeatured($value)
 * @method static Builder<static>|Product whereIsPinned($value)
 * @method static Builder<static>|Product whereMetaDescription($value)
 * @method static Builder<static>|Product whereMetaTitle($value)
 * @method static Builder<static>|Product wherePrice($value)
 * @method static Builder<static>|Product wherePublishedAt($value)
 * @method static Builder<static>|Product whereSku($value)
 * @method static Builder<static>|Product whereSlug($value)
 * @method static Builder<static>|Product whereStatus($value)
 * @method static Builder<static>|Product whereStock($value)
 * @method static Builder<static>|Product whereTitle($value)
 * @method static Builder<static>|Product whereUlid($value)
 * @method static Builder<static>|Product whereUmkmId($value)
 * @method static Builder<static>|Product whereUnit($value)
 * @method static Builder<static>|Product whereUpdatedAt($value)
 * @method static Builder<static>|Product whereUserId($value)
 * @method static Builder<static>|Product whereViewCount($value)
 * @method static Builder<static>|Product whereWeight($value)
 * @method static Builder<static>|Product withDetails()
 * @method static Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Product withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class Product extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'user_id',
        'umkm_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'featured_image_path',
        'sku',
        'price',
        'stock',
        'unit',
        'weight',
        'is_featured',
        'is_pinned',
        'status',
        'published_at',
        'view_count',
        'meta_title',
        'meta_description',
        'canonical_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'weight' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'status' => PublicationStatus::class,
            'published_at' => 'datetime',
            'view_count' => 'integer',
        ];
    }

    public function getSlugSourceColumn(): string
    {
        return 'title';
    }

    /**
     * Scope a query to eager load relations.
     */
    public function scopeWithDetails(Builder $query): void
    {
        $query->with(['category', 'author', 'umkm']);
    }

    /**
     * Scope a query to only include published products.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', PublicationStatus::Published)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * Scope a query to sort by popularity.
     */
    public function scopePopular(Builder $query): void
    {
        $query->orderBy('view_count', 'desc');
    }

    /**
     * Scope a query to search products.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function ($q, $search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the author (user) that created the product.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the umkm that owns the product.
     */
    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    /**
     * Get the photos associated with the product.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class)->orderBy('sort_order');
    }
}

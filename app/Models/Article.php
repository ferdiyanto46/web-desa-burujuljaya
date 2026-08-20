<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Observers\ArticleObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Represents a news article or blog post.
 *
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content IMPORTANT: Raw HTML. Must be sanitized via HTML Purifier at the Service Layer.
 * @property string|null $featured_image_path
 * @property PublicationStatus $status
 * @property bool $is_featured
 * @property int $views Note: Increment via Queue to avoid blocking main thread.
 * @property Carbon|null $published_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_image_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $author
 * @property-read ArticleCategory|null $category
 *
 * @method static Builder<static>|Article ofCategory(string|int $category)
 * @method static Builder<static>|Article draft()
 * @method static \Database\Factories\ArticleFactory factory($count = null, $state = [])
 * @method static Builder<static>|Article featured()
 * @method static Builder<static>|Article latestPublished()
 * @method static Builder<static>|Article newModelQuery()
 * @method static Builder<static>|Article newQuery()
 * @method static Builder<static>|Article onlyTrashed()
 * @method static Builder<static>|Article published()
 * @method static Builder<static>|Article query()
 * @method static Builder<static>|Article search(string $keyword)
 * @method static Builder<static>|Article whereCategoryId($value)
 * @method static Builder<static>|Article whereContent($value)
 * @method static Builder<static>|Article whereCreatedAt($value)
 * @method static Builder<static>|Article whereDeletedAt($value)
 * @method static Builder<static>|Article whereExcerpt($value)
 * @method static Builder<static>|Article whereFeaturedImagePath($value)
 * @method static Builder<static>|Article whereId($value)
 * @method static Builder<static>|Article whereIsFeatured($value)
 * @method static Builder<static>|Article whereMetaDescription($value)
 * @method static Builder<static>|Article whereMetaTitle($value)
 * @method static Builder<static>|Article whereOgImagePath($value)
 * @method static Builder<static>|Article wherePublishedAt($value)
 * @method static Builder<static>|Article whereSlug($value)
 * @method static Builder<static>|Article whereStatus($value)
 * @method static Builder<static>|Article whereTitle($value)
 * @method static Builder<static>|Article whereUlid($value)
 * @method static Builder<static>|Article whereUpdatedAt($value)
 * @method static Builder<static>|Article whereUserId($value)
 * @method static Builder<static>|Article whereViews($value)
 * @method static Builder<static>|Article withDetails()
 * @method static Builder<static>|Article withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Article withoutTrashed()
 *
 * @mixin \Eloquent
 */
#[ObservedBy(ArticleObserver::class)]
final class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_path',
        'status',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'og_image_path',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PublicationStatus::class,
            'is_featured' => 'boolean',
            'views' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @var list<string>
     */
    protected $appends = [
        'cover_image_url',
        'featured_image_url',
    ];

    /**
     * Get the public URL for the featured image / cover image.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (empty($this->featured_image_path)) {
            return null;
        }

        if (str_starts_with($this->featured_image_path, 'http://') || str_starts_with($this->featured_image_path, 'https://')) {
            return $this->featured_image_path;
        }

        return '/storage/'.ltrim($this->featured_image_path, '/');
    }

    /**
     * Alias for cover_image_url.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->getCoverImageUrlAttribute();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Get the author of the article.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category of the article.
     *
     * @return BelongsTo<ArticleCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * Define the source column for slug generation.
     */
    public function getSlugSourceColumn(): string
    {
        return 'title';
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Query Scopes
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Scope a query to eager load common relations to prevent N+1 issues.
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with(['author', 'category']);
    }

    /**
     * Scope a query to only include published articles that have passed their publish date.
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope a query to only include draft articles.
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Draft);
    }

    /**
     * Scope a query to only include featured articles.
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include articles for a specific category (ID or slug).
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopeOfCategory(Builder $query, int|string $category): Builder
    {
        if (is_numeric($category)) {
            return $query->where('category_id', $category);
        }

        return $query->whereHas('category', function (Builder $q) use ($category) {
            $q->where('slug', $category);
        });
    }

    /**
     * Scope a query to search across title, excerpt, and content.
     * Using native LIKE as a simple fallback, but FULLTEXT should be used for performance
     * on large datasets via MATCH AGAINST.
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('excerpt', 'like', "%{$keyword}%")
                ->orWhere('content', 'like', "%{$keyword}%");
        });
    }

    /**
     * Scope a query to order by published_at descending.
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }
}

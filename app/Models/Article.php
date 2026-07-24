<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ArticleStatus;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Database\Factories\ArticleFactory;
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
 * @property ArticleStatus $status
 * @property bool $is_featured
 * @property int $views Note: Increment via Queue to avoid blocking main thread.
 * @property Carbon|null $published_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_image_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
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
            'status' => ArticleStatus::class,
            'is_featured' => 'boolean',
            'views' => 'integer',
            'published_at' => 'datetime',
        ];
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
        return $query->where('status', ArticleStatus::Published)
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include draft articles.
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', ArticleStatus::Draft);
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
    public function scopeCategory(Builder $query, int|string $category): Builder
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
        return $query->whereFullText(['title', 'excerpt', 'content'], $keyword);
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

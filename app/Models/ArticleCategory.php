<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUniqueSlug;
use Database\Factories\ArticleCategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Represents a category for organizing articles.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Article> $articles
 * @property-read int|null $articles_count
 *
 * @method static \Database\Factories\ArticleCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleCategory withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class ArticleCategory extends Model
{
    /** @use HasFactory<ArticleCategoryFactory> */
    use HasFactory;

    use HasUniqueSlug;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Get the articles for the category.
     *
     * @return HasMany<Article, $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    /**
     * Define the source column for slug generation.
     */
    public function getSlugSourceColumn(): string
    {
        return 'name';
    }
}

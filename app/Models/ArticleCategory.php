<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUniqueSlug;
use Database\Factories\ArticleCategoryFactory;
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

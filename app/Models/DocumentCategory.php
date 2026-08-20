<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_active
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Document> $documents
 * @property-read int|null $documents_count
 *
 * @method static Builder<static>|DocumentCategory active()
 * @method static \Database\Factories\DocumentCategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|DocumentCategory newModelQuery()
 * @method static Builder<static>|DocumentCategory newQuery()
 * @method static Builder<static>|DocumentCategory query()
 * @method static Builder<static>|DocumentCategory whereCreatedAt($value)
 * @method static Builder<static>|DocumentCategory whereId($value)
 * @method static Builder<static>|DocumentCategory whereIsActive($value)
 * @method static Builder<static>|DocumentCategory whereName($value)
 * @method static Builder<static>|DocumentCategory whereSlug($value)
 * @method static Builder<static>|DocumentCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class DocumentCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the documents for the category.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}

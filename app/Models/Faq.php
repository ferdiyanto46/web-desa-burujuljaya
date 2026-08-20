<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Database\Factories\FaqFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $ulid
 * @property string $question
 * @property string $slug
 * @property string $answer
 * @property string|null $category
 * @property int $sort_order
 * @property bool $is_featured
 * @property PublicationStatus $status
 * @property CarbonImmutable|null $published_at
 * @property int $view_count
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $canonical_url
 * @property int|null $user_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $author
 *
 * @method static \Database\Factories\FaqFactory factory($count = null, $state = [])
 * @method static Builder<static>|Faq featured()
 * @method static Builder<static>|Faq latest()
 * @method static Builder<static>|Faq newModelQuery()
 * @method static Builder<static>|Faq newQuery()
 * @method static Builder<static>|Faq onlyTrashed()
 * @method static Builder<static>|Faq ordered()
 * @method static Builder<static>|Faq published()
 * @method static Builder<static>|Faq query()
 * @method static Builder<static>|Faq search(?string $search)
 * @method static Builder<static>|Faq whereAnswer($value)
 * @method static Builder<static>|Faq whereCanonicalUrl($value)
 * @method static Builder<static>|Faq whereCategory($value)
 * @method static Builder<static>|Faq whereCreatedAt($value)
 * @method static Builder<static>|Faq whereDeletedAt($value)
 * @method static Builder<static>|Faq whereId($value)
 * @method static Builder<static>|Faq whereIsFeatured($value)
 * @method static Builder<static>|Faq whereMetaDescription($value)
 * @method static Builder<static>|Faq whereMetaTitle($value)
 * @method static Builder<static>|Faq wherePublishedAt($value)
 * @method static Builder<static>|Faq whereQuestion($value)
 * @method static Builder<static>|Faq whereSlug($value)
 * @method static Builder<static>|Faq whereSortOrder($value)
 * @method static Builder<static>|Faq whereStatus($value)
 * @method static Builder<static>|Faq whereUlid($value)
 * @method static Builder<static>|Faq whereUpdatedAt($value)
 * @method static Builder<static>|Faq whereUserId($value)
 * @method static Builder<static>|Faq whereViewCount($value)
 * @method static Builder<static>|Faq withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Faq withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Faq extends Model
{
    /** @use HasFactory<FaqFactory> */
    use HasFactory;

    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $fillable = [
        'question',
        'slug',
        'answer',
        'category',
        'sort_order',
        'is_featured',
        'status',
        'published_at',
        'view_count',
        'meta_title',
        'meta_description',
        'canonical_url',
        'user_id',
    ];

    protected $casts = [
        'status' => PublicationStatus::class,
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'view_count' => 'integer',
    ];

    /**
     * Get the user that authored the faq.
     *
     * @return BelongsTo<User, covariant self>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param  Builder<Faq>  $query
     * @return Builder<Faq>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * @param  Builder<Faq>  $query
     * @return Builder<Faq>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * @param  Builder<Faq>  $query
     * @return Builder<Faq>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc');
    }

    /**
     * @param  Builder<Faq>  $query
     * @return Builder<Faq>
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * @param  Builder<Faq>  $query
     * @return Builder<Faq>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('question', 'like', "%{$search}%")
                ->orWhere('answer', 'like', "%{$search}%");
        });
    }

    public function getSlugSourceColumn(): string
    {
        return 'question';
    }
}

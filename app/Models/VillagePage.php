<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Enums\VillagePageType;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $ulid
 * @property VillagePageType $page_type
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content
 * @property string|null $featured_image_path
 * @property PublicationStatus $status
 * @property CarbonImmutable|null $published_at
 * @property bool $is_pinned
 * @property int $sort_order
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $canonical_url
 * @property int|null $user_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $author
 *
 * @method static \Database\Factories\VillagePageFactory factory($count = null, $state = [])
 * @method static Builder<static>|VillagePage newModelQuery()
 * @method static Builder<static>|VillagePage newQuery()
 * @method static Builder<static>|VillagePage onlyTrashed()
 * @method static Builder<static>|VillagePage ordered()
 * @method static Builder<static>|VillagePage published()
 * @method static Builder<static>|VillagePage query()
 * @method static Builder<static>|VillagePage search(?string $search)
 * @method static Builder<static>|VillagePage whereCanonicalUrl($value)
 * @method static Builder<static>|VillagePage whereContent($value)
 * @method static Builder<static>|VillagePage whereCreatedAt($value)
 * @method static Builder<static>|VillagePage whereDeletedAt($value)
 * @method static Builder<static>|VillagePage whereExcerpt($value)
 * @method static Builder<static>|VillagePage whereFeaturedImagePath($value)
 * @method static Builder<static>|VillagePage whereId($value)
 * @method static Builder<static>|VillagePage whereIsPinned($value)
 * @method static Builder<static>|VillagePage whereMetaDescription($value)
 * @method static Builder<static>|VillagePage whereMetaTitle($value)
 * @method static Builder<static>|VillagePage wherePageType($value)
 * @method static Builder<static>|VillagePage wherePublishedAt($value)
 * @method static Builder<static>|VillagePage whereSlug($value)
 * @method static Builder<static>|VillagePage whereSortOrder($value)
 * @method static Builder<static>|VillagePage whereStatus($value)
 * @method static Builder<static>|VillagePage whereTitle($value)
 * @method static Builder<static>|VillagePage whereUlid($value)
 * @method static Builder<static>|VillagePage whereUpdatedAt($value)
 * @method static Builder<static>|VillagePage whereUserId($value)
 * @method static Builder<static>|VillagePage withDetails()
 * @method static Builder<static>|VillagePage withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|VillagePage withoutTrashed()
 *
 * @mixin \Eloquent
 */
class VillagePage extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $fillable = [
        'page_type',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_path',
        'status',
        'published_at',
        'is_pinned',
        'sort_order',
        'meta_title',
        'meta_description',
        'canonical_url',
        'user_id',
    ];

    protected $casts = [
        'status' => PublicationStatus::class,
        'page_type' => VillagePageType::class,
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param  Builder<VillagePage>  $query
     * @return Builder<VillagePage>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * @param  Builder<VillagePage>  $query
     * @return Builder<VillagePage>
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with('author:id,name');
    }

    /**
     * @param  Builder<VillagePage>  $query
     * @return Builder<VillagePage>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc');
    }

    /**
     * @param  Builder<VillagePage>  $query
     * @return Builder<VillagePage>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }

    /**
     * The attributes that should be used when generating the unique slug.
     */
    public function getSlugSourceColumn(): string
    {
        return 'title';
    }
}

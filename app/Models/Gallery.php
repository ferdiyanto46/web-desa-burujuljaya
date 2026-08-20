<?php

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

/**
 * @property int $id
 * @property string $ulid
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $featured_image_path
 * @property PublicationStatus $status
 * @property CarbonImmutable|null $published_at
 * @property bool $is_pinned
 * @property int $user_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $author
 * @property-read Collection<int, GalleryPhoto> $photos
 * @property-read int|null $photos_count
 *
 * @method static \Database\Factories\GalleryFactory factory($count = null, $state = [])
 * @method static Builder<static>|Gallery newModelQuery()
 * @method static Builder<static>|Gallery newQuery()
 * @method static Builder<static>|Gallery onlyTrashed()
 * @method static Builder<static>|Gallery published()
 * @method static Builder<static>|Gallery query()
 * @method static Builder<static>|Gallery whereCreatedAt($value)
 * @method static Builder<static>|Gallery whereDeletedAt($value)
 * @method static Builder<static>|Gallery whereDescription($value)
 * @method static Builder<static>|Gallery whereFeaturedImagePath($value)
 * @method static Builder<static>|Gallery whereId($value)
 * @method static Builder<static>|Gallery whereIsPinned($value)
 * @method static Builder<static>|Gallery wherePublishedAt($value)
 * @method static Builder<static>|Gallery whereSlug($value)
 * @method static Builder<static>|Gallery whereStatus($value)
 * @method static Builder<static>|Gallery whereTitle($value)
 * @method static Builder<static>|Gallery whereUlid($value)
 * @method static Builder<static>|Gallery whereUpdatedAt($value)
 * @method static Builder<static>|Gallery whereUserId($value)
 * @method static Builder<static>|Gallery withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Gallery withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Gallery extends Model
{
    use HasFactory, SoftDeletes;
    use HasUlid;
    use HasUniqueSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'featured_image_path',
        'status',
        'published_at',
        'is_pinned',
        'user_id',
    ];

    protected $casts = [
        'status' => PublicationStatus::class,
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $appends = [
        'featured_image_url',
    ];

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->featured_image_path) {
            return str_starts_with($this->featured_image_path, 'http')
                ? $this->featured_image_path
                : '/storage/'.ltrim($this->featured_image_path, '/');
        }

        $firstPhoto = $this->photos->first();
        if ($firstPhoto && $firstPhoto->image_path) {
            return str_starts_with($firstPhoto->image_path, 'http')
                ? $firstPhoto->image_path
                : '/storage/'.ltrim($firstPhoto->image_path, '/');
        }

        return null;
    }

    /**
     * Scope a query to only include published galleries.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', PublicationStatus::Published)
            ->where('published_at', '<=', now());
    }
}

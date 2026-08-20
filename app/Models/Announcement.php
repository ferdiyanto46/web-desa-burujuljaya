<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Observers\AnnouncementObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Database\Factories\AnnouncementFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Represents an announcement for the village.
 *
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content IMPORTANT: Raw HTML. Must be sanitized via HTML Purifier at the Service Layer.
 * @property PublicationStatus $status
 * @property bool $is_pinned
 * @property string|null $featured_image_path
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User|null $author
 *
 * @method static Builder<static>|Announcement active()
 * @method static \Database\Factories\AnnouncementFactory factory($count = null, $state = [])
 * @method static Builder<static>|Announcement newModelQuery()
 * @method static Builder<static>|Announcement newQuery()
 * @method static Builder<static>|Announcement onlyTrashed()
 * @method static Builder<static>|Announcement query()
 * @method static Builder<static>|Announcement whereContent($value)
 * @method static Builder<static>|Announcement whereCreatedAt($value)
 * @method static Builder<static>|Announcement whereDeletedAt($value)
 * @method static Builder<static>|Announcement whereExcerpt($value)
 * @method static Builder<static>|Announcement whereFeaturedImagePath($value)
 * @method static Builder<static>|Announcement whereId($value)
 * @method static Builder<static>|Announcement whereIsPinned($value)
 * @method static Builder<static>|Announcement wherePublishedAt($value)
 * @method static Builder<static>|Announcement whereSlug($value)
 * @method static Builder<static>|Announcement whereStatus($value)
 * @method static Builder<static>|Announcement whereTitle($value)
 * @method static Builder<static>|Announcement whereUlid($value)
 * @method static Builder<static>|Announcement whereUpdatedAt($value)
 * @method static Builder<static>|Announcement whereUserId($value)
 * @method static Builder<static>|Announcement withDetails()
 * @method static Builder<static>|Announcement withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Announcement withoutTrashed()
 *
 * @mixin \Eloquent
 */
#[ObservedBy(AnnouncementObserver::class)]
final class Announcement extends Model
{
    /** @use HasFactory<AnnouncementFactory> */
    use HasFactory;

    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'is_pinned',
        'featured_image_path',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PublicationStatus::class,
            'is_pinned' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Get the author of the announcement.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
     * @param  Builder<Announcement>  $query
     * @return Builder<Announcement>
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with(['author']);
    }

    /**
     * Scope a query to only include published and active announcements.
     *
     * @param  Builder<Announcement>  $query
     * @return Builder<Announcement>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}

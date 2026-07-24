<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ArticleStatus;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Database\Factories\AnnouncementFactory;
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
 * @property ArticleStatus $status
 * @property bool $is_pinned
 * @property Carbon|null $end_date
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
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
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'is_pinned',
        'end_date',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'is_pinned' => 'boolean',
            'end_date' => 'datetime',
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
        return $query->where('status', ArticleStatus::Published)
            ->where(function (Builder $q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}

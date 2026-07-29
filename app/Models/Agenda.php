<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ArticleStatus;
use App\Observers\AgendaObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Database\Factories\AgendaFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Represents an agenda or event for the village.
 *
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $content IMPORTANT: Raw HTML. Must be sanitized via HTML Purifier at the Service Layer.
 * @property string $location
 * @property Carbon $start_datetime
 * @property Carbon|null $end_datetime
 * @property ArticleStatus $status
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[ObservedBy(AgendaObserver::class)]
final class Agenda extends Model
{
    /** @use HasFactory<AgendaFactory> */
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
        'content',
        'location',
        'featured_image_path',
        'location_url',
        'start_datetime',
        'end_datetime',
        'status',
        'is_cancelled',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'is_cancelled' => 'boolean',
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Get the author of the agenda.
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

    /**
     * Get the dynamic event status based on datetime and cancellation.
     */
    public function getEventStatusAttribute(): string
    {
        if ($this->is_cancelled) {
            return 'cancelled';
        }

        $now = now();
        if ($now->isBefore($this->start_datetime)) {
            return 'upcoming';
        }

        if ($this->end_datetime && $now->isAfter($this->end_datetime)) {
            return 'finished';
        }

        return 'ongoing';
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Query Scopes
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Scope a query to eager load common relations to prevent N+1 issues.
     *
     * @param  Builder<Agenda>  $query
     * @return Builder<Agenda>
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with(['author']);
    }

    /**
     * Scope a query to only include published and upcoming agendas.
     *
     * @param  Builder<Agenda>  $query
     * @return Builder<Agenda>
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', ArticleStatus::Published)
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc');
    }
}

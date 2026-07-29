<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\ComplaintObserver;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ComplaintObserver::class)]
final class Complaint extends Model
{
    use HasFactory;
    use HasUlid;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'title',
        'body',
        'is_anonymous',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include unread complaints.
     */
    public function scopeUnread(Builder $query): void
    {
        $query->where('status', 'unread');
    }

    /**
     * Scope a query to only include open/processing complaints.
     */
    public function scopeOpen(Builder $query): void
    {
        $query->where('status', 'processing');
    }

    /**
     * Scope a query to only include resolved or rejected complaints.
     */
    public function scopeClosed(Builder $query): void
    {
        $query->whereIn('status', ['resolved', 'rejected']);
    }

    /**
     * Scope a query to only include public complaints (example).
     */
    public function scopePublic(Builder $query): void
    {
        // Could be customized based on business logic
        $query->where('is_anonymous', false);
    }

    /**
     * Scope a query to include relationships to avoid N+1.
     */
    public function scopeWithDetails(Builder $query): void
    {
        $query->with(['category', 'attachments', 'histories.user']);
    }

    /**
     * Get the category that owns the complaint.
     *
     * @return BelongsTo<ComplaintCategory, covariant $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class);
    }

    /**
     * Get the attachments for the complaint.
     *
     * @return HasMany<ComplaintAttachment>
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    /**
     * Get the histories for the complaint.
     *
     * @return HasMany<ComplaintHistory>
     */
    public function histories(): HasMany
    {
        return $this->hasMany(ComplaintHistory::class);
    }
}

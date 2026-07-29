<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\TourismObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(TourismObserver::class)]
final class Tourism extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'address',
        'cover_image',
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
     * Scope a query to eager load relations.
     */
    public function scopeWithDetails(Builder $query): void
    {
        $query->with(['category', 'user']);
    }

    /**
     * Scope a query to only include active tourism.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * The slug source field.
     */
    public function slugSource(): string
    {
        return 'name';
    }

    /**
     * Get the category that owns the tourism.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TourismCategory::class, 'category_id');
    }

    /**
     * Get the user that owns the tourism.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

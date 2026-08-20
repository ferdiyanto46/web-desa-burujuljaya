<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\ApparatusObserver;
use App\Traits\HasUlid;
use Database\Factories\ApparatusFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Represents a village official or apparatus.
 *
 * @property int $id
 * @property string $ulid
 * @property int $position_id
 * @property string $name
 * @property string|null $nip
 * @property string|null $education
 * @property string|null $photo_path
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ApparatusPosition $position
 * @property-read string|null $photo_url
 *
 * @method static \Database\Factories\ApparatusFactory factory($count = null, $state = [])
 * @method static Builder<static>|Apparatus newModelQuery()
 * @method static Builder<static>|Apparatus newQuery()
 * @method static Builder<static>|Apparatus onlyTrashed()
 * @method static Builder<static>|Apparatus query()
 * @method static Builder<static>|Apparatus whereCreatedAt($value)
 * @method static Builder<static>|Apparatus whereDeletedAt($value)
 * @method static Builder<static>|Apparatus whereEducation($value)
 * @method static Builder<static>|Apparatus whereId($value)
 * @method static Builder<static>|Apparatus whereIsActive($value)
 * @method static Builder<static>|Apparatus whereName($value)
 * @method static Builder<static>|Apparatus whereNip($value)
 * @method static Builder<static>|Apparatus wherePhotoPath($value)
 * @method static Builder<static>|Apparatus wherePositionId($value)
 * @method static Builder<static>|Apparatus whereUlid($value)
 * @method static Builder<static>|Apparatus whereUpdatedAt($value)
 * @method static Builder<static>|Apparatus withDetails()
 * @method static Builder<static>|Apparatus withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Apparatus withoutTrashed()
 *
 * @mixin \Eloquent
 */
#[ObservedBy(ApparatusObserver::class)]
final class Apparatus extends Model
{
    /** @use HasFactory<ApparatusFactory> */
    use HasFactory;

    use HasUlid;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'apparatus';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'position_id',
        'name',
        'nip',
        'education',
        'photo_path',
        'is_active',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'photo_url',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Get the position of the apparatus.
     *
     * @return BelongsTo<ApparatusPosition, $this>
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(ApparatusPosition::class, 'position_id');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Get the public URL for the apparatus photo.
     */
    public function photoUrl(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        if (config('filesystems.disks.public.driver') === 'local') {
            if (! Storage::disk('public')->exists($this->photo_path)) {
                return null;
            }

            return '/storage/'.ltrim($this->photo_path, '/');
        }

        return Storage::disk('public')->url($this->photo_path);
    }

    /**
     * Accessor for photo_url attribute.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photoUrl();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Query Scopes
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Scope a query to eager load common relations to prevent N+1 issues.
     *
     * @param  Builder<Apparatus>  $query
     * @return Builder<Apparatus>
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with(['position']);
    }
}

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

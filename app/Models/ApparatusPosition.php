<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ApparatusPositionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Represents a position or title for a village official.
 *
 * @property int $id
 * @property string $name
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Apparatus> $apparatuses
 * @property-read int|null $apparatuses_count
 *
 * @method static \Database\Factories\ApparatusPositionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApparatusPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApparatusPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApparatusPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApparatusPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApparatusPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApparatusPosition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApparatusPosition whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApparatusPosition whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class ApparatusPosition extends Model
{
    /** @use HasFactory<ApparatusPositionFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Get the apparatuses for the position.
     *
     * @return HasMany<Apparatus, $this>
     */
    public function apparatuses(): HasMany
    {
        return $this->hasMany(Apparatus::class, 'position_id');
    }
}

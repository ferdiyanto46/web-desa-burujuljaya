<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ApparatusPositionFactory;
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

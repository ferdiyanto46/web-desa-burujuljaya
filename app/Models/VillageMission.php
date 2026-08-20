<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a single mission statement bullet point for the village.
 *
 * Mission statements belong to a VillageProfile and are displayed in ascending
 * sort_order on the public-facing profile / vision-mission page.
 *
 * No soft delete: missions are replaced or reordered, not archived.
 *   When an admin deletes a mission, it is gone permanently. Historical mission
 *   statements are not a requirement for this system.
 *
 * No UUID/ULID: missions are never referenced by external systems directly.
 *   They are always accessed via the parent VillageProfile relationship.
 *
 * sort_order is stored with gaps (10, 20, 30, …) so that a new mission can be
 * inserted between two existing ones without renumbering all rows.
 *
 * @property int $id
 * @property int $village_profile_id
 * @property string $content
 * @property int $sort_order
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read VillageProfile          $villageProfile
 *
 * @method static Builder<static>|VillageMission newModelQuery()
 * @method static Builder<static>|VillageMission newQuery()
 * @method static Builder<static>|VillageMission ordered()
 * @method static Builder<static>|VillageMission query()
 * @method static Builder<static>|VillageMission whereContent($value)
 * @method static Builder<static>|VillageMission whereCreatedAt($value)
 * @method static Builder<static>|VillageMission whereId($value)
 * @method static Builder<static>|VillageMission whereSortOrder($value)
 * @method static Builder<static>|VillageMission whereUpdatedAt($value)
 * @method static Builder<static>|VillageMission whereVillageProfileId($value)
 *
 * @mixin \Eloquent
 */
final class VillageMission extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'village_profile_id',
        'content',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * @var list<string>
     */
    protected $appends = [
        'statement',
    ];

    /**
     * Alias content as statement for compatibility.
     */
    public function getStatementAttribute(): string
    {
        return $this->content ?? '';
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * @return BelongsTo<VillageProfile, $this>
     */
    public function villageProfile(): BelongsTo
    {
        return $this->belongsTo(VillageProfile::class);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Query Scopes
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Scope: order missions by sort_order ascending.
     *
     * VillageProfile::missions() already applies this order via the relationship,
     * so this scope is primarily useful when querying VillageMission directly.
     *
     * @param  Builder<VillageMission>  $query
     * @return Builder<VillageMission>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}

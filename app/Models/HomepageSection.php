<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\HomepageSectionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<HomepageSection> enabled()
 * @method static \Illuminate\Database\Eloquent\Builder<HomepageSection> ordered()
 *
 * @property mixed $aggregated_data
 * @property int $id
 * @property string $ulid
 * @property string $section_key
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $content
 * @property array<array-key, mixed>|null $configuration
 * @property int $sort_order
 * @property bool $is_enabled
 * @property string $status
 * @property CarbonImmutable|null $published_at
 * @property int|null $user_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @method static \Database\Factories\HomepageSectionFactory factory($count = null, $state = [])
 * @method static Builder<static>|HomepageSection newModelQuery()
 * @method static Builder<static>|HomepageSection newQuery()
 * @method static Builder<static>|HomepageSection onlyTrashed()
 * @method static Builder<static>|HomepageSection query()
 * @method static Builder<static>|HomepageSection whereConfiguration($value)
 * @method static Builder<static>|HomepageSection whereContent($value)
 * @method static Builder<static>|HomepageSection whereCreatedAt($value)
 * @method static Builder<static>|HomepageSection whereDeletedAt($value)
 * @method static Builder<static>|HomepageSection whereId($value)
 * @method static Builder<static>|HomepageSection whereIsEnabled($value)
 * @method static Builder<static>|HomepageSection wherePublishedAt($value)
 * @method static Builder<static>|HomepageSection whereSectionKey($value)
 * @method static Builder<static>|HomepageSection whereSortOrder($value)
 * @method static Builder<static>|HomepageSection whereStatus($value)
 * @method static Builder<static>|HomepageSection whereSubtitle($value)
 * @method static Builder<static>|HomepageSection whereTitle($value)
 * @method static Builder<static>|HomepageSection whereUlid($value)
 * @method static Builder<static>|HomepageSection whereUpdatedAt($value)
 * @method static Builder<static>|HomepageSection whereUserId($value)
 * @method static Builder<static>|HomepageSection withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|HomepageSection withoutTrashed()
 *
 * @mixin \Eloquent
 */
class HomepageSection extends Model
{
    /** @use HasFactory<HomepageSectionFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'section_key',
        'title',
        'subtitle',
        'content',
        'configuration',
        'sort_order',
        'is_enabled',
        'status',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_enabled' => 'boolean',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @param  Builder<HomepageSection>  $query
     * @return Builder<HomepageSection>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @param  Builder<HomepageSection>  $query
     * @return Builder<HomepageSection>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc');
    }
}

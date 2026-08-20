<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_active
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, VillageStatistic> $statistics
 * @property-read int|null $statistics_count
 *
 * @method static Builder<static>|StatisticCategory active()
 * @method static \Database\Factories\StatisticCategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|StatisticCategory newModelQuery()
 * @method static Builder<static>|StatisticCategory newQuery()
 * @method static Builder<static>|StatisticCategory query()
 * @method static Builder<static>|StatisticCategory whereCreatedAt($value)
 * @method static Builder<static>|StatisticCategory whereId($value)
 * @method static Builder<static>|StatisticCategory whereIsActive($value)
 * @method static Builder<static>|StatisticCategory whereName($value)
 * @method static Builder<static>|StatisticCategory whereSlug($value)
 * @method static Builder<static>|StatisticCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class StatisticCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
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
     * Get the statistics for this category.
     */
    public function statistics(): HasMany
    {
        return $this->hasMany(VillageStatistic::class, 'category_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}

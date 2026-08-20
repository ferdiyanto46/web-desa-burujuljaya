<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property int $value
 * @property int $year
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read StatisticCategory $category
 *
 * @method static Builder<static>|VillageStatistic byYear(int $year)
 * @method static \Database\Factories\VillageStatisticFactory factory($count = null, $state = [])
 * @method static Builder<static>|VillageStatistic newModelQuery()
 * @method static Builder<static>|VillageStatistic newQuery()
 * @method static Builder<static>|VillageStatistic query()
 * @method static Builder<static>|VillageStatistic whereCategoryId($value)
 * @method static Builder<static>|VillageStatistic whereCreatedAt($value)
 * @method static Builder<static>|VillageStatistic whereId($value)
 * @method static Builder<static>|VillageStatistic whereName($value)
 * @method static Builder<static>|VillageStatistic whereUpdatedAt($value)
 * @method static Builder<static>|VillageStatistic whereValue($value)
 * @method static Builder<static>|VillageStatistic whereYear($value)
 *
 * @mixin \Eloquent
 */
final class VillageStatistic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'value',
        'year',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'integer',
            'year' => 'integer',
        ];
    }

    /**
     * Get the category that owns the statistic.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(StatisticCategory::class, 'category_id');
    }

    /**
     * Scope a query to only include statistics of a given year.
     */
    public function scopeByYear(Builder $query, int $year): void
    {
        $query->where('year', $year);
    }
}

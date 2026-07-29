<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

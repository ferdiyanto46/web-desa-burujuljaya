<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\BannerObserver;
use App\Traits\HasUlid;
use Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(BannerObserver::class)]
final class Banner extends Model
{
    /** @use HasFactory<BannerFactory> */
    use HasFactory;

    use HasUlid;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'is_active',
        'sort_order',
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
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope a query to only include active banners.
     */
    /** @param Builder<static> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to order banners by sort_order.
     */
    /** @param Builder<static> $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order', 'asc');
    }
}

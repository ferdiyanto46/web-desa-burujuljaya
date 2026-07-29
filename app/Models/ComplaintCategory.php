<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ComplaintCategory extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
     * Scope a query to only include active categories.
     */
    public function scopeActiveCategory(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Get the complaints for the category.
     *
     * @return HasMany<Complaint>
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}

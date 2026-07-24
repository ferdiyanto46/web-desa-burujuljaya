<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\ProductObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ProductObserver::class)]
final class Product extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'umkm_id',
        'name',
        'slug',
        'description',
        'price',
        'unit',
        'cover_image',
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
            'price' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to eager load relations.
     */
    public function scopeWithDetails(Builder $query): void
    {
        $query->with(['category', 'user', 'umkm']);
    }

    /**
     * The slug source field.
     */
    public function slugSource(): string
    {
        return 'name';
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the umkm that owns the product.
     */
    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    /**
     * Get the user that owns the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

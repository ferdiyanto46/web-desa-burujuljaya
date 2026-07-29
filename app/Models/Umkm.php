<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\UmkmObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(UmkmObserver::class)]
final class Umkm extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $table = 'umkm';

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'owner_name',
        'description',
        'phone',
        'address',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to eager load relations.
     */
    public function scopeWithDetails(Builder $query): void
    {
        $query->with(['category', 'user']);
    }

    /**
     * Scope a query to only include active umkm.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * The slug source field.
     */
    public function slugSource(): string
    {
        return 'name';
    }

    /**
     * Get the category that owns the umkm.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(UmkmCategory::class, 'category_id');
    }

    /**
     * Get the user that owns the umkm.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the products for the umkm.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'umkm_id');
    }
}

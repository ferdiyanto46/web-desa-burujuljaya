<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $ulid
 * @property int $product_id
 * @property string $image_path
 * @property string|null $caption
 * @property string|null $alt_text
 * @property int $sort_order
 * @property int $id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read Product|null $product
 *
 * @method static \Database\Factories\ProductPhotoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPhoto withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class ProductPhoto extends Model
{
    use HasFactory;
    use HasUlid;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'image_path',
        'caption',
        'alt_text',
        'sort_order',
    ];

    /**
     * Get the product that owns the photo.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

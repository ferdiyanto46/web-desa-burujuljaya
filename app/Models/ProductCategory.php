<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $ulid
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @method static \Database\Factories\ProductCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class ProductCategory extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function getSlugSourceColumn(): string
    {
        return 'name';
    }

    protected function casts(): array
    {
        return [
            //
        ];
    }
}

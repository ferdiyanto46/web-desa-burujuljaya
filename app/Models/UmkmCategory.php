<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string|null $ulid
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Umkm> $umkms
 * @property-read int|null $umkms_count
 *
 * @method static \Database\Factories\UmkmCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class UmkmCategory extends Model
{
    use HasFactory;
    use HasUlid;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the umkms for the category.
     */
    public function umkms(): HasMany
    {
        return $this->hasMany(Umkm::class, 'category_id');
    }
}

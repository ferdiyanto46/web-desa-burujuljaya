<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property string|null $icon
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Tourism> $tourisms
 * @property-read int|null $tourisms_count
 *
 * @method static \Database\Factories\TourismFacilityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismFacility whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class TourismFacility extends Model
{
    use HasFactory;
    use HasUlid;

    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * Get the tourisms that belong to the facility.
     */
    public function tourisms(): BelongsToMany
    {
        return $this->belongsToMany(Tourism::class);
    }
}

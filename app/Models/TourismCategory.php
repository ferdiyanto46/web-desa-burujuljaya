<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @method static \Database\Factories\TourismCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismCategory withoutTrashed()
 *
 * @mixin \Eloquent
 */
class TourismCategory extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
}

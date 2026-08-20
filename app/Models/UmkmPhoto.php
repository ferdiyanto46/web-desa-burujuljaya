<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\CarbonImmutable;
use Database\Factories\UmkmPhotoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $ulid
 * @property int $umkm_id
 * @property string $file_path
 * @property string|null $caption
 * @property int $order_column
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Umkm|null $umkm
 *
 * @method static \Database\Factories\UmkmPhotoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto whereUmkmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UmkmPhoto whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class UmkmPhoto extends Model
{
    /** @use HasFactory<UmkmPhotoFactory> */
    use HasFactory;

    use HasUlid;

    protected $table = 'umkm_photos';

    protected $fillable = [
        'umkm_id',
        'file_path',
        'caption',
        'order_column',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order_column' => 'integer',
        ];
    }

    /**
     * Get the umkm that owns the photo.
     */
    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}

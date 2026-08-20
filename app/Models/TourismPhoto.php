<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $ulid
 * @property int $tourism_id
 * @property string $file_path
 * @property string|null $caption
 * @property int $order
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read string|null $file_url
 * @property-read Tourism|null $tourism
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto whereTourismId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourismPhoto whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class TourismPhoto extends Model
{
    use HasFactory;
    use HasUlid;

    protected $fillable = [
        'tourism_id',
        'file_path',
        'caption',
        'order',
    ];

    /**
     * Get the tourism that owns the photo.
     */
    public function tourism(): BelongsTo
    {
        return $this->belongsTo(Tourism::class);
    }

    /**
     * Get the full URL for the file.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::disk('public')->url($this->file_path) : null;
    }
}

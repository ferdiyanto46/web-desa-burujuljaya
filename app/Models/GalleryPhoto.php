<?php

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $ulid
 * @property int $gallery_id
 * @property string $image_path
 * @property string|null $caption
 * @property string|null $alt_text
 * @property string|null $photographer
 * @property int|null $file_size
 * @property int|null $width
 * @property int|null $height
 * @property string|null $mime
 * @property string|null $extension
 * @property string|null $disk
 * @property int $sort_order
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read Gallery|null $gallery
 *
 * @method static \Database\Factories\GalleryPhotoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereGalleryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto wherePhotographer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPhoto withoutTrashed()
 *
 * @mixin \Eloquent
 */
class GalleryPhoto extends Model
{
    use HasFactory, SoftDeletes;
    use HasUlid;

    protected $fillable = [
        'gallery_id',
        'image_path',
        'caption',
        'alt_text',
        'photographer',
        'file_size',
        'width',
        'height',
        'mime',
        'extension',
        'disk',
        'sort_order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return '/storage/'.ltrim($this->image_path, '/');
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }
}

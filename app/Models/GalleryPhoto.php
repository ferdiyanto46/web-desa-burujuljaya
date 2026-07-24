<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\GalleryPhotoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(GalleryPhotoObserver::class)]
final class GalleryPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id',
        'title',
        'photo_path',
        'size',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'order' => 'integer',
        ];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }
}

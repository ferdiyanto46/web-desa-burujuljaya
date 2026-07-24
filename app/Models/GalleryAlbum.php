<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\GalleryAlbumObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(GalleryAlbumObserver::class)]
final class GalleryAlbum extends Model
{
    use HasFactory, HasUlid, HasUniqueSlug, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'cover_photo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class, 'album_id')->orderBy('order');
    }

    public function scopeWithDetails(Builder $query): void
    {
        $query->with(['user:id,name', 'photos:id,album_id,title,photo_path,size,order']);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}

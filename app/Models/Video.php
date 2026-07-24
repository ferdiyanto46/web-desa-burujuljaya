<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Video extends Model
{
    use HasFactory, HasUlid, HasUniqueSlug, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'video_url',
        'thumbnail_url',
        'is_active',
        'video_id', // explicitly fillable but we can override it
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

    protected function videoUrl(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                return [
                    'video_url' => $value,
                    'video_id' => $this->extractYoutubeId($value),
                ];
            },
        );
    }

    private function extractYoutubeId(string $url): ?string
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';
        if (preg_match($pattern, $url, $match)) {
            return $match[1];
        }

        return null;
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}

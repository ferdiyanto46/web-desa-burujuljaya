<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\VideoObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $ulid
 * @property string $title
 * @property string $slug
 * @property string $video_url
 * @property string|null $video_id
 * @property string|null $thumbnail_url
 * @property bool $is_active
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $user
 *
 * @method static Builder<static>|Video active()
 * @method static \Database\Factories\VideoFactory factory($count = null, $state = [])
 * @method static Builder<static>|Video newModelQuery()
 * @method static Builder<static>|Video newQuery()
 * @method static Builder<static>|Video onlyTrashed()
 * @method static Builder<static>|Video query()
 * @method static Builder<static>|Video whereCreatedAt($value)
 * @method static Builder<static>|Video whereDeletedAt($value)
 * @method static Builder<static>|Video whereId($value)
 * @method static Builder<static>|Video whereIsActive($value)
 * @method static Builder<static>|Video whereSlug($value)
 * @method static Builder<static>|Video whereThumbnailUrl($value)
 * @method static Builder<static>|Video whereTitle($value)
 * @method static Builder<static>|Video whereUlid($value)
 * @method static Builder<static>|Video whereUpdatedAt($value)
 * @method static Builder<static>|Video whereUserId($value)
 * @method static Builder<static>|Video whereVideoId($value)
 * @method static Builder<static>|Video whereVideoUrl($value)
 * @method static Builder<static>|Video withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Video withoutTrashed()
 *
 * @mixin \Eloquent
 */
#[ObservedBy(VideoObserver::class)]
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

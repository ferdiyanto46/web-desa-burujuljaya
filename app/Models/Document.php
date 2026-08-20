<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Observers\DocumentObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property int $category_id
 * @property PublicationStatus $status
 * @property CarbonImmutable|null $published_at
 * @property bool $is_pinned
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string $file_path
 * @property string $file_name
 * @property string $extension
 * @property string $mime
 * @property int $file_size
 * @property string $disk
 * @property int $download_count
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $author
 * @property-read DocumentCategory $category
 *
 * @method static \Database\Factories\DocumentFactory factory($count = null, $state = [])
 * @method static Builder<static>|Document newModelQuery()
 * @method static Builder<static>|Document newQuery()
 * @method static Builder<static>|Document onlyTrashed()
 * @method static Builder<static>|Document pinned()
 * @method static Builder<static>|Document published()
 * @method static Builder<static>|Document query()
 * @method static Builder<static>|Document search(string $term)
 * @method static Builder<static>|Document whereCategoryId($value)
 * @method static Builder<static>|Document whereCreatedAt($value)
 * @method static Builder<static>|Document whereDeletedAt($value)
 * @method static Builder<static>|Document whereDescription($value)
 * @method static Builder<static>|Document whereDisk($value)
 * @method static Builder<static>|Document whereDownloadCount($value)
 * @method static Builder<static>|Document whereExtension($value)
 * @method static Builder<static>|Document whereFileName($value)
 * @method static Builder<static>|Document whereFilePath($value)
 * @method static Builder<static>|Document whereFileSize($value)
 * @method static Builder<static>|Document whereId($value)
 * @method static Builder<static>|Document whereIsPinned($value)
 * @method static Builder<static>|Document whereMime($value)
 * @method static Builder<static>|Document wherePublishedAt($value)
 * @method static Builder<static>|Document whereSlug($value)
 * @method static Builder<static>|Document whereStatus($value)
 * @method static Builder<static>|Document whereTitle($value)
 * @method static Builder<static>|Document whereUlid($value)
 * @method static Builder<static>|Document whereUpdatedAt($value)
 * @method static Builder<static>|Document whereUserId($value)
 * @method static Builder<static>|Document withDetails()
 * @method static Builder<static>|Document withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Document withoutTrashed()
 *
 * @mixin \Eloquent
 */
#[ObservedBy(DocumentObserver::class)]
final class Document extends Model
{
    use HasFactory;
    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ulid',
        'category_id',
        'title',
        'slug',
        'description',
        'file_path',
        'file_name',
        'mime',
        'extension',
        'file_size',
        'disk',
        'status',
        'published_at',
        'is_pinned',
        'download_count',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PublicationStatus::class,
            'published_at' => 'datetime',
            'is_pinned' => 'boolean',
            'file_size' => 'integer',
            'download_count' => 'integer',
        ];
    }

    /**
     * Get the source string for generating the slug.
     */
    protected function slugSource(): string
    {
        return 'title';
    }

    /**
     * Get the category that owns the document.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    /**
     * Get the user that uploaded the document.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to eager load common relationships.
     */
    public function scopeWithDetails(Builder $query): void
    {
        $query->with(['category', 'author']);
    }

    /**
     * Scope a query to only include published documents.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', PublicationStatus::Published)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope a query to only include pinned documents.
     */
    public function scopePinned(Builder $query): void
    {
        $query->where('is_pinned', true);
    }

    /**
     * Scope a query to search documents.
     */
    public function scopeSearch(Builder $query, string $term): void
    {
        $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}

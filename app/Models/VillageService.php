<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Database\Factories\VillageServiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $ulid
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $description
 * @property string|null $featured_image_path
 * @property string|null $requirements
 * @property string|null $procedures
 * @property string|null $processing_time
 * @property string|null $cost
 * @property string|null $service_hours
 * @property string|null $contact_person
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $downloadable_form_path
 * @property string|null $downloadable_form_name
 * @property string|null $downloadable_form_disk
 * @property int|null $downloadable_form_size
 * @property string|null $downloadable_form_mime
 * @property string|null $downloadable_form_extension
 * @property PublicationStatus $status
 * @property CarbonImmutable|null $published_at
 * @property bool $is_pinned
 * @property int $sort_order
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $canonical_url
 * @property string|null $og_image
 * @property int|null $user_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $author
 *
 * @method static \Database\Factories\VillageServiceFactory factory($count = null, $state = [])
 * @method static Builder<static>|VillageService newModelQuery()
 * @method static Builder<static>|VillageService newQuery()
 * @method static Builder<static>|VillageService onlyTrashed()
 * @method static Builder<static>|VillageService ordered()
 * @method static Builder<static>|VillageService pinned()
 * @method static Builder<static>|VillageService published()
 * @method static Builder<static>|VillageService query()
 * @method static Builder<static>|VillageService search(?string $search)
 * @method static Builder<static>|VillageService whereCanonicalUrl($value)
 * @method static Builder<static>|VillageService whereContactPerson($value)
 * @method static Builder<static>|VillageService whereCost($value)
 * @method static Builder<static>|VillageService whereCreatedAt($value)
 * @method static Builder<static>|VillageService whereDeletedAt($value)
 * @method static Builder<static>|VillageService whereDescription($value)
 * @method static Builder<static>|VillageService whereDownloadableFormDisk($value)
 * @method static Builder<static>|VillageService whereDownloadableFormExtension($value)
 * @method static Builder<static>|VillageService whereDownloadableFormMime($value)
 * @method static Builder<static>|VillageService whereDownloadableFormName($value)
 * @method static Builder<static>|VillageService whereDownloadableFormPath($value)
 * @method static Builder<static>|VillageService whereDownloadableFormSize($value)
 * @method static Builder<static>|VillageService whereEmail($value)
 * @method static Builder<static>|VillageService whereExcerpt($value)
 * @method static Builder<static>|VillageService whereFeaturedImagePath($value)
 * @method static Builder<static>|VillageService whereId($value)
 * @method static Builder<static>|VillageService whereIsPinned($value)
 * @method static Builder<static>|VillageService whereMetaDescription($value)
 * @method static Builder<static>|VillageService whereMetaTitle($value)
 * @method static Builder<static>|VillageService whereOgImage($value)
 * @method static Builder<static>|VillageService wherePhone($value)
 * @method static Builder<static>|VillageService whereProcedures($value)
 * @method static Builder<static>|VillageService whereProcessingTime($value)
 * @method static Builder<static>|VillageService wherePublishedAt($value)
 * @method static Builder<static>|VillageService whereRequirements($value)
 * @method static Builder<static>|VillageService whereServiceHours($value)
 * @method static Builder<static>|VillageService whereSlug($value)
 * @method static Builder<static>|VillageService whereSortOrder($value)
 * @method static Builder<static>|VillageService whereStatus($value)
 * @method static Builder<static>|VillageService whereTitle($value)
 * @method static Builder<static>|VillageService whereUlid($value)
 * @method static Builder<static>|VillageService whereUpdatedAt($value)
 * @method static Builder<static>|VillageService whereUserId($value)
 * @method static Builder<static>|VillageService withDetails()
 * @method static Builder<static>|VillageService withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|VillageService withoutTrashed()
 *
 * @mixin \Eloquent
 */
class VillageService extends Model
{
    /** @use HasFactory<VillageServiceFactory> */
    use HasFactory;

    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'featured_image_path',
        'requirements',
        'procedures',
        'processing_time',
        'cost',
        'service_hours',
        'contact_person',
        'phone',
        'email',
        'downloadable_form_path',
        'downloadable_form_name',
        'downloadable_form_disk',
        'downloadable_form_size',
        'downloadable_form_mime',
        'downloadable_form_extension',
        'status',
        'published_at',
        'is_pinned',
        'sort_order',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'user_id',
    ];

    protected $casts = [
        'status' => PublicationStatus::class,
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
        'sort_order' => 'integer',
        'downloadable_form_size' => 'integer',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param  Builder<VillageService>  $query
     * @return Builder<VillageService>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * @param  Builder<VillageService>  $query
     * @return Builder<VillageService>
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with('author:id,name');
    }

    /**
     * @param  Builder<VillageService>  $query
     * @return Builder<VillageService>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc');
    }

    /**
     * @param  Builder<VillageService>  $query
     * @return Builder<VillageService>
     */
    public function scopePinned(Builder $query): Builder
    {
        return $query->where('is_pinned', true);
    }

    /**
     * @param  Builder<VillageService>  $query
     * @return Builder<VillageService>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function getSlugSourceColumn(): string
    {
        return 'title';
    }
}

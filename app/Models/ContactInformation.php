<?php

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Carbon\CarbonImmutable;
use Database\Factories\ContactInformationFactory;
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
 * @property string|null $description
 * @property string|null $address
 * @property string|null $google_maps_embed
 * @property string|null $google_maps_url
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $whatsapp
 * @property string|null $email
 * @property string|null $website
 * @property string|null $facebook
 * @property string|null $instagram
 * @property string|null $youtube
 * @property string|null $tiktok
 * @property string|null $office_hours
 * @property string|null $emergency_contact
 * @property string|null $featured_image_path
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
 * @method static \Database\Factories\ContactInformationFactory factory($count = null, $state = [])
 * @method static Builder<static>|ContactInformation newModelQuery()
 * @method static Builder<static>|ContactInformation newQuery()
 * @method static Builder<static>|ContactInformation onlyTrashed()
 * @method static Builder<static>|ContactInformation ordered()
 * @method static Builder<static>|ContactInformation pinned()
 * @method static Builder<static>|ContactInformation published()
 * @method static Builder<static>|ContactInformation query()
 * @method static Builder<static>|ContactInformation search(?string $search)
 * @method static Builder<static>|ContactInformation whereAddress($value)
 * @method static Builder<static>|ContactInformation whereCanonicalUrl($value)
 * @method static Builder<static>|ContactInformation whereCreatedAt($value)
 * @method static Builder<static>|ContactInformation whereDeletedAt($value)
 * @method static Builder<static>|ContactInformation whereDescription($value)
 * @method static Builder<static>|ContactInformation whereEmail($value)
 * @method static Builder<static>|ContactInformation whereEmergencyContact($value)
 * @method static Builder<static>|ContactInformation whereExcerpt($value)
 * @method static Builder<static>|ContactInformation whereFacebook($value)
 * @method static Builder<static>|ContactInformation whereFeaturedImagePath($value)
 * @method static Builder<static>|ContactInformation whereGoogleMapsEmbed($value)
 * @method static Builder<static>|ContactInformation whereGoogleMapsUrl($value)
 * @method static Builder<static>|ContactInformation whereId($value)
 * @method static Builder<static>|ContactInformation whereInstagram($value)
 * @method static Builder<static>|ContactInformation whereIsPinned($value)
 * @method static Builder<static>|ContactInformation whereMetaDescription($value)
 * @method static Builder<static>|ContactInformation whereMetaTitle($value)
 * @method static Builder<static>|ContactInformation whereMobile($value)
 * @method static Builder<static>|ContactInformation whereOfficeHours($value)
 * @method static Builder<static>|ContactInformation whereOgImage($value)
 * @method static Builder<static>|ContactInformation wherePhone($value)
 * @method static Builder<static>|ContactInformation wherePublishedAt($value)
 * @method static Builder<static>|ContactInformation whereSlug($value)
 * @method static Builder<static>|ContactInformation whereSortOrder($value)
 * @method static Builder<static>|ContactInformation whereStatus($value)
 * @method static Builder<static>|ContactInformation whereTiktok($value)
 * @method static Builder<static>|ContactInformation whereTitle($value)
 * @method static Builder<static>|ContactInformation whereUlid($value)
 * @method static Builder<static>|ContactInformation whereUpdatedAt($value)
 * @method static Builder<static>|ContactInformation whereUserId($value)
 * @method static Builder<static>|ContactInformation whereWebsite($value)
 * @method static Builder<static>|ContactInformation whereWhatsapp($value)
 * @method static Builder<static>|ContactInformation whereYoutube($value)
 * @method static Builder<static>|ContactInformation withDetails()
 * @method static Builder<static>|ContactInformation withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|ContactInformation withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ContactInformation extends Model
{
    /** @use HasFactory<ContactInformationFactory> */
    use HasFactory;

    use HasUlid;
    use HasUniqueSlug;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'address',
        'google_maps_embed',
        'google_maps_url',
        'phone',
        'mobile',
        'whatsapp',
        'email',
        'website',
        'facebook',
        'instagram',
        'youtube',
        'tiktok',
        'office_hours',
        'emergency_contact',
        'featured_image_path',
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
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param  Builder<ContactInformation>  $query
     * @return Builder<ContactInformation>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * @param  Builder<ContactInformation>  $query
     * @return Builder<ContactInformation>
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with('author:id,name');
    }

    /**
     * @param  Builder<ContactInformation>  $query
     * @return Builder<ContactInformation>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc');
    }

    /**
     * @param  Builder<ContactInformation>  $query
     * @return Builder<ContactInformation>
     */
    public function scopePinned(Builder $query): Builder
    {
        return $query->where('is_pinned', true);
    }

    /**
     * @param  Builder<ContactInformation>  $query
     * @return Builder<ContactInformation>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        });
    }

    public function getSlugSourceColumn(): string
    {
        return 'title';
    }
}

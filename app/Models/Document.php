<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\DocumentObserver;
use App\Traits\HasUlid;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'user_id',
        'category_id',
        'title',
        'description',
        'file_path',
        'file_extension',
        'mime_type',
        'file_size',
        'is_public',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'download_count' => 'integer',
            'is_public' => 'boolean',
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include public documents.
     */
    public function scopePublic(Builder $query): void
    {
        $query->where('is_public', true);
    }

    /**
     * Scope a query to eager load common relationships.
     */
    public function scopeWithDetails(Builder $query): void
    {
        $query->with(['category', 'user']);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\ComplaintAttachmentObserver;
use App\Traits\HasUlid;
use Database\Factories\ComplaintAttachmentFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $ulid
 * @property int $complaint_id
 * @property string $file_path
 * @property string $file_name
 * @property string $file_type
 * @property int $file_size
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[ObservedBy(ComplaintAttachmentObserver::class)]
final class ComplaintAttachment extends Model
{
    /** @use HasFactory<ComplaintAttachmentFactory> */
    use HasFactory;

    use HasUlid;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'complaint_id',
        'file_path',
        'file_type',
        'file_size',
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
        ];
    }

    /**
     * Get the file name from path.
     */
    public function getFileNameAttribute(): string
    {
        return basename($this->file_path);
    }

    /**
     * Get the public URL of the attachment.
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/'.$this->file_path);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * Get the complaint that owns the attachment.
     *
     * @return BelongsTo<Complaint, covariant $this>
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $ulid
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $subject
 * @property string $message
 * @property bool $is_read
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @method static \Database\Factories\ContactMessageFactory factory($count = null, $state = [])
 * @method static Builder<static>|ContactMessage newModelQuery()
 * @method static Builder<static>|ContactMessage newQuery()
 * @method static Builder<static>|ContactMessage onlyTrashed()
 * @method static Builder<static>|ContactMessage query()
 * @method static Builder<static>|ContactMessage unread()
 * @method static Builder<static>|ContactMessage whereCreatedAt($value)
 * @method static Builder<static>|ContactMessage whereDeletedAt($value)
 * @method static Builder<static>|ContactMessage whereEmail($value)
 * @method static Builder<static>|ContactMessage whereId($value)
 * @method static Builder<static>|ContactMessage whereIsRead($value)
 * @method static Builder<static>|ContactMessage whereMessage($value)
 * @method static Builder<static>|ContactMessage whereName($value)
 * @method static Builder<static>|ContactMessage wherePhone($value)
 * @method static Builder<static>|ContactMessage whereSubject($value)
 * @method static Builder<static>|ContactMessage whereUlid($value)
 * @method static Builder<static>|ContactMessage whereUpdatedAt($value)
 * @method static Builder<static>|ContactMessage withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|ContactMessage withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class ContactMessage extends Model
{
    use HasFactory;
    use HasUlid;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread(Builder $query): void
    {
        $query->where('is_read', false);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $complaint_id
 * @property string $actor_type
 * @property int|null $user_id
 * @property string|null $status_from
 * @property string $status_to
 * @property string|null $notes
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Complaint|null $complaint
 * @property-read User|null $user
 *
 * @method static \Database\Factories\ComplaintHistoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereActorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereComplaintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereStatusFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereStatusTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintHistory whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class ComplaintHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'complaint_id',
        'actor_type',
        'user_id',
        'status_from',
        'status_to',
        'notes',
    ];

    /**
     * Get the complaint that owns the history.
     *
     * @return BelongsTo<Complaint, covariant $this>
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * Get the user that owns the history (if actor is admin).
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

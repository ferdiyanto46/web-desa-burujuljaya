<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Database\Factories\ActivityLogFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class ActivityLog extends Model
{
    /** @use HasFactory<ActivityLogFactory> */
    use HasFactory;

    use HasUlid;

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'batch_uuid',
        'log_name',
        'event',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    /**
     * Get the subject model of the activity.
     *
     * @return MorphTo<Model, covariant $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the causer model of the activity.
     *
     * @return MorphTo<Model, covariant $this>
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include logs with the given log name(s).
     *
     * @param  array<string>|string  $logNames
     */
    /** @param Builder<static> $query */
    public function scopeInLog(Builder $query, array|string $logNames): void
    {
        $logNames = (array) $logNames;
        $query->whereIn('log_name', $logNames);
    }

    /**
     * Scope a query to only include logs caused by the given model.
     */
    /** @param Builder<static> $query */
    public function scopeCausedBy(Builder $query, Model $causer): void
    {
        $query->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }

    /**
     * Scope a query to only include logs for the given subject model.
     */
    /** @param Builder<static> $query */
    public function scopeForSubject(Builder $query, Model $subject): void
    {
        $query->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

final class ActivityLogService
{
    /**
     * Log an activity to the database.
     *
     * @param  string  $event  The event type (e.g., created, updated, deleted)
     * @param  string  $description  Human-readable description
     * @param  Model|null  $subject  The model being operated on
     * @param  Model|null  $causer  The user or model causing the activity
     * @param  array<string, mixed>  $properties  Structured JSON properties (old, attributes, extra)
     * @param  string  $logName  The category of the log
     * @param  string|null  $batchUuid  Batch UUID to group related logs
     */
    public function log(
        string $event,
        string $description,
        ?Model $subject = null,
        ?Model $causer = null,
        array $properties = [],
        string $logName = 'default',
        ?string $batchUuid = null
    ): ActivityLog {
        return ActivityLog::create([
            'batch_uuid' => $batchUuid,
            'log_name' => $logName,
            'event' => $event,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'causer_type' => $causer?->getMorphClass(),
            'causer_id' => $causer?->getKey(),
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}

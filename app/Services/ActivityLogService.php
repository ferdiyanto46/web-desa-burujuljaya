<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class ActivityLogService
{
    /**
     * Create a new activity log entry.
     */
    public function log(
        string $event,
        string $description,
        ?Model $subject = null,
        ?Model $causer = null,
        array $properties = [],
        string $logName = 'default'
    ): void {
        $request = app(Request::class);
        $causer = $causer ?? auth()->user();

        ActivityLog::create([
            'log_name' => $logName,
            'event' => $event,
            'description' => $description,
            'subject_type' => $subject ? $subject->getMorphClass() : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'causer_type' => $causer ? $causer->getMorphClass() : null,
            'causer_id' => $causer ? $causer->getKey() : null,
            'properties' => $properties,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit($request->userAgent() ?? '', 250),
        ]);
    }
}

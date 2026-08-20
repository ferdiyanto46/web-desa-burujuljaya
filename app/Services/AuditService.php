<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class AuditService
{
    /**
     * Get security-related audit logs.
     */
    public function getSecurityLogs(?string $search): LengthAwarePaginator
    {
        return ActivityLog::with('causer:id,name')
            ->whereIn('event', ['login', 'logout', 'failed_login', 'updated', 'deleted', 'forceDeleted'])
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('description', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%")
                        ->orWhereHas('causer', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                }
            })
            // If it's updated or deleted, filter for specific security models like User, Role, Permission
            ->where(function ($query) {
                $query->whereIn('event', ['login', 'logout', 'failed_login'])
                    ->orWhere(function ($q) {
                        $q->whereIn('event', ['updated', 'deleted', 'forceDeleted'])
                            ->whereIn('subject_type', [
                                User::class,
                                Role::class,
                                Permission::class,
                                Setting::class,
                            ]);
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }
}

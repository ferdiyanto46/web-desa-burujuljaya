<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class AuditController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService
    ) {}

    /**
     * Display a listing of security audit logs.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', ActivityLog::class); // Reusing activity log permission

        $search = $request->query('search');
        $search = is_string($search) ? $search : null;

        $logs = $this->auditService->getSecurityLogs($search);

        return Inertia::render('Admin/Audit/Index', [
            'logs' => $logs,
            'filters' => $request->only(['search']),
        ]);
    }
}

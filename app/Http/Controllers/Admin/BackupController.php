<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class BackupController extends Controller
{
    public function __construct(
        private readonly BackupService $backupService,
        private readonly ActivityLogService $activityLogService
    ) {}

    /**
     * Display a listing of the backups.
     */
    public function index(): Response
    {
        Gate::authorize('manage backups');

        $backups = $this->backupService->getBackups();

        return Inertia::render('Admin/Backup/Index', [
            'backups' => $backups,
        ]);
    }

    /**
     * Create a new backup.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage backups');

        $onlyDatabase = $request->boolean('only_database');

        try {
            $this->backupService->runBackup($onlyDatabase);

            $this->activityLogService->log(
                event: 'created',
                description: 'Membuat backup baru ('.($onlyDatabase ? 'Database' : 'Full').')',
            );

            return back()->with('success', 'Backup berhasil dimulai di latar belakang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat backup: '.$e->getMessage());
        }
    }

    /**
     * Download the specified backup.
     */
    public function download(string $fileName): BinaryFileResponse|RedirectResponse
    {
        Gate::authorize('manage backups');

        try {
            $this->activityLogService->log(
                event: 'downloaded',
                description: "Mengunduh file backup: {$fileName}",
            );

            return $this->backupService->downloadBackup($fileName);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunduh backup: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified backup.
     */
    public function destroy(string $fileName): RedirectResponse
    {
        Gate::authorize('manage backups');

        try {
            $this->backupService->deleteBackup($fileName);

            $this->activityLogService->log(
                event: 'deleted',
                description: "Menghapus file backup: {$fileName}",
            );

            return back()->with('success', 'Backup berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus backup: '.$e->getMessage());
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class BackupService
{
    /**
     * Get all backups from the disk.
     */
    public function getBackups(): array
    {
        $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
        $disk = Storage::disk($diskName);
        $backupName = config('backup.backup.name');

        $files = $disk->files($backupName);
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $backups[] = [
                    'path' => $file,
                    'file_name' => basename($file),
                    'size' => $disk->size($file),
                    'date' => $disk->lastModified($file),
                ];
            }
        }

        // Sort by date descending
        usort($backups, fn ($a, $b) => $b['date'] <=> $a['date']);

        return $backups;
    }

    /**
     * Run the backup command.
     */
    public function runBackup(bool $onlyDatabase = false): void
    {
        $command = 'backup:run';
        $parameters = [];

        if ($onlyDatabase) {
            $parameters['--only-db'] = true;
        }

        Artisan::call($command, $parameters);
    }

    /**
     * Download a backup file.
     */
    public function downloadBackup(string $fileName): BinaryFileResponse
    {
        $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
        $disk = Storage::disk($diskName);
        $backupName = config('backup.backup.name');
        $path = $backupName.'/'.$fileName;

        abort_if(! $disk->exists($path), 404, 'Backup file not found.');

        return response()->download($disk->path($path));
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackup(string $fileName): void
    {
        $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
        $disk = Storage::disk($diskName);
        $backupName = config('backup.backup.name');
        $path = $backupName.'/'.$fileName;

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }
}

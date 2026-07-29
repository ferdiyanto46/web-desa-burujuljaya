<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class MediaService
{
    /**
     * Store a new file.
     */
    public function store(UploadedFile $file, string $path = 'uploads', string $disk = 'public'): ?string
    {
        $storedPath = $file->store($path, $disk);
        return is_string($storedPath) ? $storedPath : null;
    }

    /**
     * Replace an old file with a new one.
     */
    public function replace(UploadedFile $newFile, ?string $oldPath, string $path = 'uploads', string $disk = 'public'): ?string
    {
        $this->delete($oldPath, $disk);
        return $this->store($newFile, $path, $disk);
    }

    /**
     * Delete a file if it exists.
     */
    public function delete(?string $filePath, string $disk = 'public'): void
    {
        if ($filePath && Storage::disk($disk)->exists($filePath)) {
            Storage::disk($disk)->delete($filePath);
        }
    }
}

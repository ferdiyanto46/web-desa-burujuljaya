<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Announcement;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Mews\Purifier\Facades\Purifier;

final class AnnouncementService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Broadcast a new announcement.
     *
     * @param  array<string, mixed>  $data
     */
    public function broadcast(array $data, ?UploadedFile $featuredImage = null): Announcement
    {
        return $this->db->transaction(function () use ($data, $featuredImage) {
            $announcement = clone new Announcement;

            $data['content'] = Purifier::clean($data['content'] ?? '');

            if ($featuredImage) {
                $data['featured_image_path'] = $this->mediaService->store($featuredImage, 'announcements');
            }

            $announcement->fill($data);
            $announcement->save();

            return $announcement;
        });
    }

    /**
     * Update an existing announcement.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Announcement $announcement, array $data, ?UploadedFile $featuredImage = null): Announcement
    {
        return $this->db->transaction(function () use ($announcement, $data, $featuredImage) {
            if (isset($data['content']) && is_string($data['content'])) {
                $data['content'] = Purifier::clean($data['content']);
            }

            if ($featuredImage) {
                $data['featured_image_path'] = $this->mediaService->replace(
                    $featuredImage,
                    $announcement->featured_image_path,
                    'announcements'
                );
            }

            $announcement->fill($data);
            $announcement->save();

            return $announcement;
        });
    }

    /**
     * Revoke (soft delete) an announcement.
     */
    public function revoke(Announcement $announcement): void
    {
        $announcement->delete();
    }

    /**
     * Restore a soft-deleted announcement.
     */
    public function restore(Announcement $announcement): void
    {
        $announcement->restore();
    }

    /**
     * Permanently delete an announcement and its media.
     */
    public function forceDelete(Announcement $announcement): void
    {
        if ($announcement->featured_image_path) {
            $this->mediaService->delete($announcement->featured_image_path);
        }

        $announcement->forceDelete();
    }
}

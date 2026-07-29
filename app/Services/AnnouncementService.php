<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Announcement;
use Illuminate\Database\DatabaseManager;
use Mews\Purifier\Facades\Purifier;

final class AnnouncementService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Broadcast a new announcement.
     *
     * @param  array<string, mixed>  $data
     */
    public function broadcast(array $data): Announcement
    {
        return $this->db->transaction(function () use ($data) {
            $announcement = clone new Announcement;

            $data['content'] = Purifier::clean($data['content'] ?? '');

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
    public function update(Announcement $announcement, array $data): Announcement
    {
        return $this->db->transaction(function () use ($announcement, $data) {
            if (isset($data['content']) && is_string($data['content'])) {
                $data['content'] = Purifier::clean($data['content']);
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
}

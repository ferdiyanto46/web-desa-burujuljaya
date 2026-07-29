<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Video;
use Illuminate\Database\DatabaseManager;

final class VideoService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Embed a new video.
     *
     * @param  array<string, mixed>  $data
     */
    public function embed(array $data): Video
    {
        return $this->db->transaction(function () use ($data) {
            $video = clone new Video;

            $video->fill($data);
            $video->save();

            return $video;
        });
    }

    /**
     * Update an existing video.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Video $video, array $data): Video
    {
        return $this->db->transaction(function () use ($video, $data) {
            $video->fill($data);
            $video->save();

            return $video;
        });
    }

    /**
     * Remove (soft delete) a video.
     */
    public function remove(Video $video): void
    {
        $video->delete();
    }
}

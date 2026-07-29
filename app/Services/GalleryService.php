<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;

final class GalleryService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Create a new gallery album.
     *
     * @param  array<string, mixed>  $data
     */
    public function createAlbum(array $data, ?UploadedFile $cover = null): GalleryAlbum
    {
        return $this->db->transaction(function () use ($data, $cover) {
            $album = clone new GalleryAlbum;

            if ($cover) {
                $path = $cover->store('gallery/covers', 'public');
                if (is_string($path)) {
                    $data['cover_photo'] = $path;
                }
            }

            $album->fill($data);
            $album->save();

            return $album;
        });
    }

    /**
     * Update an existing gallery album.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateAlbum(GalleryAlbum $album, array $data, ?UploadedFile $cover = null): GalleryAlbum
    {
        return $this->db->transaction(function () use ($album, $data, $cover) {
            if ($cover) {
                $path = $cover->store('gallery/covers', 'public');
                if (is_string($path)) {
                    $data['cover_photo'] = $path;
                }
            }

            $album->fill($data);
            $album->save();

            return $album;
        });
    }

    /**
     * Delete an album.
     */
    public function deleteAlbum(GalleryAlbum $album): void
    {
        $album->delete();
    }

    /**
     * Upload photos to an album.
     *
     * @param  array<UploadedFile>  $photos
     * @return array<int, GalleryPhoto>
     */
    public function uploadPhotos(GalleryAlbum $album, array $photos): array
    {
        return $this->db->transaction(function () use ($album, $photos) {
            /** @var array<int, GalleryPhoto> $uploadedPhotos */
            $uploadedPhotos = [];
            $maxOrder = $album->photos()->max('order') ?? 0;

            foreach ($photos as $photo) {
                $path = $photo->store('gallery/photos', 'public');

                if (is_string($path)) {
                    $maxOrder++;
                    /** @var GalleryPhoto $newPhoto */
                    $newPhoto = $album->photos()->create([
                        'title' => $photo->getClientOriginalName(),
                        'photo_path' => $path,
                        'size' => $photo->getSize(),
                        'order' => $maxOrder,
                    ]);
                    $uploadedPhotos[] = $newPhoto;
                }
            }

            return $uploadedPhotos;
        });
    }

    /**
     * Remove a photo from an album.
     */
    public function removePhoto(GalleryPhoto $photo): void
    {
        $photo->delete();
    }
}

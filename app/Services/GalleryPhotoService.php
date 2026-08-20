<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class GalleryPhotoService
{
    public function __construct(
        private readonly MediaService $mediaService
    ) {}

    /**
     * Upload and store multiple photos for a gallery.
     *
     * @param  UploadedFile[]  $photos
     * @return Collection
     */
    public function uploadPhotos(Gallery $gallery, array $photos)
    {
        return DB::transaction(function () use ($gallery, $photos) {
            $uploadedPhotos = [];

            // Get current max sort_order
            $maxOrder = $gallery->photos()->max('sort_order') ?? 0;

            foreach ($photos as $index => $photo) {
                // Upload image using MediaService
                $imagePath = $this->mediaService->store($photo, "galleries/{$gallery->ulid}");

                // Get metadata
                $size = $photo->getSize();
                $mime = $photo->getMimeType();
                $extension = $photo->getClientOriginalExtension();

                // Get dimensions if it's an image
                $width = null;
                $height = null;
                if (str_starts_with($mime ?? '', 'image/')) {
                    $dimensions = @getimagesize($photo->getRealPath());
                    if ($dimensions) {
                        $width = $dimensions[0];
                        $height = $dimensions[1];
                    }
                }

                $galleryPhoto = GalleryPhoto::create([
                    'gallery_id' => $gallery->id,
                    'image_path' => $imagePath,
                    'file_size' => $size,
                    'width' => $width,
                    'height' => $height,
                    'mime' => $mime,
                    'extension' => $extension,
                    'disk' => config('filesystems.default'),
                    'sort_order' => $maxOrder + $index + 1,
                ]);

                $uploadedPhotos[] = $galleryPhoto;
            }

            return collect($uploadedPhotos);
        });
    }

    /**
     * Update metadata for a single photo.
     */
    public function updatePhoto(GalleryPhoto $photo, array $data): GalleryPhoto
    {
        $photo->update($data);

        return $photo;
    }

    /**
     * Replace a single photo with a new file.
     */
    public function replacePhoto(GalleryPhoto $photo, UploadedFile $newFile): GalleryPhoto
    {
        return DB::transaction(function () use ($photo, $newFile) {
            // Delete old file
            $this->mediaService->delete($photo->image_path);

            // Upload new file
            $imagePath = $this->mediaService->store($newFile, "galleries/{$photo->gallery->ulid}");

            // Update metadata
            $size = $newFile->getSize();
            $mime = $newFile->getMimeType();
            $extension = $newFile->getClientOriginalExtension();

            $width = null;
            $height = null;
            if (str_starts_with($mime ?? '', 'image/')) {
                $dimensions = @getimagesize($newFile->getRealPath());
                if ($dimensions) {
                    $width = $dimensions[0];
                    $height = $dimensions[1];
                }
            }

            $photo->update([
                'image_path' => $imagePath,
                'file_size' => $size,
                'width' => $width,
                'height' => $height,
                'mime' => $mime,
                'extension' => $extension,
            ]);

            return $photo;
        });
    }

    /**
     * Reorder photos in a gallery.
     *
     * @param  array  $photoUlids  Array of photo ULIDs in their new order
     */
    public function reorderPhotos(Gallery $gallery, array $photoUlids): void
    {
        DB::transaction(function () use ($gallery, $photoUlids) {
            foreach ($photoUlids as $index => $ulid) {
                GalleryPhoto::where('gallery_id', $gallery->id)
                    ->where('ulid', $ulid)
                    ->update(['sort_order' => $index + 1]);
            }
        });
    }

    /**
     * Soft delete a photo.
     */
    public function deletePhoto(GalleryPhoto $photo): void
    {
        $photo->delete();
    }

    /**
     * Restore a soft-deleted photo.
     */
    public function restorePhoto(GalleryPhoto $photo): void
    {
        $photo->restore();
    }

    /**
     * Permanently delete a photo and its file.
     */
    public function forceDeletePhoto(GalleryPhoto $photo): void
    {
        DB::transaction(function () use ($photo) {
            $this->mediaService->delete($photo->image_path);
            $photo->forceDelete();
        });
    }
}

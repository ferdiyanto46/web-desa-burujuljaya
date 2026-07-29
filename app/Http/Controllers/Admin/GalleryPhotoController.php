<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadGalleryPhotoRequest;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\GalleryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;

final class GalleryPhotoController extends Controller
{
    public function __construct(
        private readonly GalleryService $galleryService
    ) {}

    /**
     * Store newly uploaded photos to an album.
     */
    public function store(UploadGalleryPhotoRequest $request, GalleryAlbum $album): RedirectResponse
    {
        /** @var array<UploadedFile> $photos */
        $photos = $request->file('photos');

        $this->galleryService->uploadPhotos($album, $photos);

        return redirect()->route('admin.gallery-albums.show', $album)->with('success', 'Foto berhasil diunggah.');
    }

    /**
     * Remove the specified photo from the album.
     */
    public function destroy(GalleryAlbum $album, GalleryPhoto $photo): RedirectResponse
    {
        $this->galleryService->removePhoto($photo);

        return redirect()->route('admin.gallery-albums.show', $album)->with('success', 'Foto berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReorderGalleryPhotoRequest;
use App\Http\Requests\StoreGalleryPhotoRequest;
use App\Http\Requests\UpdateGalleryPhotoRequest;
use App\Models\Gallery;
use App\Models\GalleryPhoto;
use App\Services\GalleryPhotoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class GalleryPhotoController extends Controller
{
    public function __construct(
        private readonly GalleryPhotoService $galleryPhotoService
    ) {}

    /**
     * Store bulk photos for a gallery.
     */
    public function store(StoreGalleryPhotoRequest $request, Gallery $gallery): RedirectResponse
    {
        // Policy checked via request
        $this->galleryPhotoService->uploadPhotos(
            $gallery,
            $request->file('photos')
        );

        return redirect()->back()
            ->with('success', 'Foto berhasil diunggah.');
    }

    /**
     * Update metadata for a single photo.
     */
    public function update(UpdateGalleryPhotoRequest $request, Gallery $gallery, GalleryPhoto $photo): RedirectResponse
    {
        // Policy checked via request
        $this->galleryPhotoService->updatePhoto($photo, $request->validated());

        return redirect()->back()
            ->with('success', 'Data foto berhasil diperbarui.');
    }

    /**
     * Reorder photos.
     */
    public function reorder(ReorderGalleryPhotoRequest $request, Gallery $gallery): RedirectResponse
    {
        // Policy checked via request
        $this->galleryPhotoService->reorderPhotos($gallery, $request->validated('photos'));

        return redirect()->back()
            ->with('success', 'Urutan foto berhasil disimpan.');
    }

    /**
     * Remove the specified photo from storage.
     */
    public function destroy(Gallery $gallery, GalleryPhoto $photo): RedirectResponse
    {
        Gate::authorize('delete', $photo);

        $this->galleryPhotoService->deletePhoto($photo);

        return redirect()->back()
            ->with('success', 'Foto berhasil dihapus.');
    }
}

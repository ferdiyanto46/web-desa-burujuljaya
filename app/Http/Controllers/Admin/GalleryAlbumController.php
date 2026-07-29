<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryAlbumRequest;
use App\Http\Requests\Admin\UpdateGalleryAlbumRequest;
use App\Models\GalleryAlbum;
use App\Services\GalleryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class GalleryAlbumController extends Controller
{
    public function __construct(
        private readonly GalleryService $galleryService
    ) {}

    /**
     * Display a listing of the gallery albums.
     */
    public function index(): Response
    {
        $albums = GalleryAlbum::withDetails()->latest()->paginate(15);

        return Inertia::render('Admin/Gallery/Albums/Index', [
            'albums' => $albums,
        ]);
    }

    /**
     * Show the form for creating a new gallery album.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Gallery/Albums/Create');
    }

    /**
     * Store a newly created gallery album in storage.
     */
    public function store(StoreGalleryAlbumRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $coverPhoto */
        $coverPhoto = $request->file('cover_photo');

        $this->galleryService->createAlbum($data, $coverPhoto);

        return redirect()->route('admin.gallery-albums.index')->with('success', 'Album berhasil dibuat.');
    }

    /**
     * Display the specified gallery album (show photos inside).
     */
    public function show(GalleryAlbum $galleryAlbum): Response
    {
        $galleryAlbum->load('photos');

        return Inertia::render('Admin/Gallery/Albums/Show', [
            'album' => $galleryAlbum,
        ]);
    }

    /**
     * Show the form for editing the specified gallery album.
     */
    public function edit(GalleryAlbum $galleryAlbum): Response
    {
        return Inertia::render('Admin/Gallery/Albums/Edit', [
            'album' => $galleryAlbum,
        ]);
    }

    /**
     * Update the specified gallery album in storage.
     */
    public function update(UpdateGalleryAlbumRequest $request, GalleryAlbum $galleryAlbum): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $coverPhoto */
        $coverPhoto = $request->file('cover_photo');

        $this->galleryService->updateAlbum($galleryAlbum, $data, $coverPhoto);

        return redirect()->route('admin.gallery-albums.index')->with('success', 'Album berhasil diperbarui.');
    }

    /**
     * Remove the specified gallery album from storage.
     */
    public function destroy(GalleryAlbum $galleryAlbum): RedirectResponse
    {
        $this->galleryService->deleteAlbum($galleryAlbum);

        return redirect()->route('admin.gallery-albums.index')->with('success', 'Album berhasil dihapus.');
    }
}

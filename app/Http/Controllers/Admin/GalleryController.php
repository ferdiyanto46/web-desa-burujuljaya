<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Services\GalleryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    public function __construct(
        private readonly GalleryService $galleryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Gallery::class);

        $filters = $request->only(['search', 'status', 'is_pinned']);
        $galleries = $this->galleryService->getAdminPaginated($filters);

        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => GalleryResource::collection($galleries),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Gallery::class);

        return Inertia::render('Admin/Galleries/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $gallery = $this->galleryService->createGallery(
            $request->validated(),
            $request->user()
        );

        return redirect()->route('admin.galleries.edit', $gallery->ulid)
            ->with('success', 'Galeri berhasil dibuat. Silakan tambahkan foto.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery): Response
    {
        Gate::authorize('update', $gallery);

        $gallery->load(['photos' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        return Inertia::render('Admin/Galleries/Edit', [
            'gallery' => new GalleryResource($gallery),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $this->galleryService->updateGallery($gallery, $request->validated());

        return redirect()->back()
            ->with('success', 'Galeri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        Gate::authorize('delete', $gallery);

        $this->galleryService->deleteGallery($gallery);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil dihapus.');
    }
}

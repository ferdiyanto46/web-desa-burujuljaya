<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Services\GalleryService;
use Inertia\Inertia;
use Inertia\Response;

final class GalleryController extends Controller
{
    public function __construct(
        private readonly GalleryService $galleryService
    ) {}

    /**
     * Display a listing of gallery albums.
     */
    public function index(): Response
    {
        $galleries = $this->galleryService->getPublicPaginated();

        return Inertia::render('Public/Gallery/Index', [
            'galleries' => GalleryResource::collection($galleries),
        ]);
    }

    /**
     * Display the specified gallery album (and its photos).
     */
    public function show(string $slug): Response
    {
        $gallery = Gallery::query()
            ->where('slug', $slug)
            ->where('status', PublicationStatus::Published)
            ->with(['author', 'photos' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->firstOrFail();

        return Inertia::render('Public/Gallery/Show', [
            'gallery' => new GalleryResource($gallery),
        ]);
    }
}

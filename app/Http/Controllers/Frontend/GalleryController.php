<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use Inertia\Inertia;
use Inertia\Response;

final class GalleryController extends Controller
{
    /**
     * Display a listing of gallery albums.
     */
    public function index(): Response
    {
        $albums = GalleryAlbum::withDetails()
            ->latest()
            ->paginate(12);

        return Inertia::render('Public/Gallery/Index', [
            'albums' => $albums,
        ]);
    }

    /**
     * Display the specified gallery album (and its photos).
     */
    public function show(string $slug): Response
    {
        $album = GalleryAlbum::withDetails()
            ->where('slug', $slug)
            ->firstOrFail();

        $album->load('photos');

        return Inertia::render('Public/Gallery/Show', [
            'album' => $album,
        ]);
    }
}

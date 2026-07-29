<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourismRequest;
use App\Http\Requests\Admin\UpdateTourismRequest;
use App\Models\Tourism;
use App\Models\TourismCategory;
use App\Services\TourismService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class TourismController extends Controller
{
    public function __construct(
        private readonly TourismService $tourismService
    ) {}

    /**
     * Display a listing of the tourism spots.
     */
    public function index(): Response
    {
        $tourisms = Tourism::withDetails()->latest()->paginate(15);

        return Inertia::render('Admin/Tourism/Index', [
            'tourisms' => $tourisms,
        ]);
    }

    /**
     * Show the form for creating a new tourism spot.
     */
    public function create(): Response
    {
        $categories = TourismCategory::all();

        return Inertia::render('Admin/Tourism/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created tourism spot in storage.
     */
    public function store(StoreTourismRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $coverImage */
        $coverImage = $request->file('cover_image');

        $this->tourismService->create($data, $coverImage);

        return redirect()->route('admin.tourism.index')->with('success', 'Wisata berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified tourism spot.
     */
    public function edit(Tourism $tourism): Response
    {
        $categories = TourismCategory::all();

        return Inertia::render('Admin/Tourism/Edit', [
            'tourism' => $tourism,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified tourism spot in storage.
     */
    public function update(UpdateTourismRequest $request, Tourism $tourism): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $coverImage */
        $coverImage = $request->file('cover_image');

        $this->tourismService->update($tourism, $data, $coverImage);

        return redirect()->route('admin.tourism.index')->with('success', 'Wisata berhasil diperbarui.');
    }

    /**
     * Remove the specified tourism spot from storage.
     */
    public function destroy(Tourism $tourism): RedirectResponse
    {
        $this->tourismService->remove($tourism);

        return redirect()->route('admin.tourism.index')->with('success', 'Wisata berhasil dihapus.');
    }
}

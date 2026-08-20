<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourismRequest;
use App\Http\Requests\UpdateTourismRequest;
use App\Http\Resources\TourismCategoryResource;
use App\Http\Resources\TourismFacilityResource;
use App\Http\Resources\TourismResource;
use App\Models\Tourism;
use App\Services\TourismCategoryService;
use App\Services\TourismFacilityService;
use App\Services\TourismService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class TourismController extends Controller
{
    public function __construct(
        private readonly TourismService $service,
        private readonly TourismCategoryService $categoryService,
        private readonly TourismFacilityService $facilityService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Tourism::class);

        $tourisms = $this->service->getAdminPaginated(
            filters: $request->only(['search', 'status', 'category_id']),
            perPage: (int) $request->input('per_page', 10)
        );

        $categories = $this->categoryService->getAll();

        return Inertia::render('Admin/Tourism/Index', [
            'tourisms' => TourismResource::collection($tourisms),
            'categories' => TourismCategoryResource::collection($categories),
            'filters' => $request->only(['search', 'status', 'category_id']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Tourism::class);

        $categories = $this->categoryService->getAll();
        $facilities = $this->facilityService->getAll();

        return Inertia::render('Admin/Tourism/Create', [
            'categories' => TourismCategoryResource::collection($categories),
            'facilities' => TourismFacilityResource::collection($facilities),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTourismRequest $request): RedirectResponse
    {
        $this->authorize('create', Tourism::class);

        $this->service->store($request->validated(), (int) Auth::id());

        return redirect()->route('admin.tourisms.index')
            ->with('success', 'Destinasi wisata berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tourism $tourism): Response
    {
        $this->authorize('view', $tourism);

        $tourism->load(['category', 'author', 'facilities', 'photos']);

        return Inertia::render('Admin/Tourism/Show', [
            'tourism' => new TourismResource($tourism),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tourism $tourism): Response
    {
        $this->authorize('update', $tourism);

        $tourism->load(['facilities', 'photos']);
        $categories = $this->categoryService->getAll();
        $facilities = $this->facilityService->getAll();

        return Inertia::render('Admin/Tourism/Edit', [
            'tourism' => new TourismResource($tourism),
            'categories' => TourismCategoryResource::collection($categories),
            'facilities' => TourismFacilityResource::collection($facilities),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTourismRequest $request, Tourism $tourism): RedirectResponse
    {
        $this->authorize('update', $tourism);

        $this->service->update($tourism, $request->validated());

        return redirect()->route('admin.tourisms.index')
            ->with('success', 'Destinasi wisata berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tourism $tourism): RedirectResponse
    {
        $this->authorize('delete', $tourism);

        $this->service->delete($tourism);

        return redirect()->route('admin.tourisms.index')
            ->with('success', 'Destinasi wisata berhasil diarsipkan.');
    }

    /**
     * Restore the specified resource.
     */
    public function restore(Tourism $tourism): RedirectResponse
    {
        $this->authorize('restore', $tourism);

        $this->service->restore($tourism);

        return back()->with('success', 'Destinasi wisata berhasil dipulihkan.');
    }

    /**
     * Force delete the specified resource.
     */
    public function forceDelete(Tourism $tourism): RedirectResponse
    {
        $this->authorize('forceDelete', $tourism);

        $this->service->forceDelete($tourism);

        return back()->with('success', 'Destinasi wisata berhasil dihapus permanen.');
    }
}

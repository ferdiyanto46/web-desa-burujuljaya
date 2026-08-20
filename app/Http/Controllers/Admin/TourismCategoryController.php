<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourismCategoryRequest;
use App\Http\Requests\UpdateTourismCategoryRequest;
use App\Http\Resources\TourismCategoryResource;
use App\Models\TourismCategory;
use App\Services\TourismCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class TourismCategoryController extends Controller
{
    public function __construct(
        private readonly TourismCategoryService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', TourismCategory::class);

        $categories = $this->service->getPaginated(
            filters: $request->only(['search']),
            perPage: (int) $request->input('per_page', 10)
        );

        return Inertia::render('Admin/Tourism/Category/Index', [
            'categories' => TourismCategoryResource::collection($categories),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTourismCategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', TourismCategory::class);

        $this->service->store($request->validated());

        return redirect()->route('admin.tourism-categories.index')
            ->with('success', 'Kategori wisata berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTourismCategoryRequest $request, TourismCategory $tourismCategory): RedirectResponse
    {
        $this->authorize('update', $tourismCategory);

        $this->service->update($tourismCategory, $request->validated());

        return redirect()->route('admin.tourism-categories.index')
            ->with('success', 'Kategori wisata berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TourismCategory $tourismCategory): RedirectResponse
    {
        $this->authorize('delete', $tourismCategory);

        $this->service->delete($tourismCategory);

        return redirect()->route('admin.tourism-categories.index')
            ->with('success', 'Kategori wisata berhasil dihapus.');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTourismFacilityRequest;
use App\Http\Requests\UpdateTourismFacilityRequest;
use App\Http\Resources\TourismFacilityResource;
use App\Models\TourismFacility;
use App\Services\TourismFacilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class TourismFacilityController extends Controller
{
    public function __construct(
        private readonly TourismFacilityService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', TourismFacility::class);

        $facilities = $this->service->getPaginated(
            filters: $request->only(['search']),
            perPage: (int) $request->input('per_page', 10)
        );

        return Inertia::render('Admin/Tourism/Facility/Index', [
            'facilities' => TourismFacilityResource::collection($facilities),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTourismFacilityRequest $request): RedirectResponse
    {
        $this->authorize('create', TourismFacility::class);

        $this->service->store($request->validated());

        return redirect()->route('admin.tourism-facilities.index')
            ->with('success', 'Fasilitas wisata berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTourismFacilityRequest $request, TourismFacility $tourismFacility): RedirectResponse
    {
        $this->authorize('update', $tourismFacility);

        $this->service->update($tourismFacility, $request->validated());

        return redirect()->route('admin.tourism-facilities.index')
            ->with('success', 'Fasilitas wisata berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TourismFacility $tourismFacility): RedirectResponse
    {
        $this->authorize('delete', $tourismFacility);

        $this->service->delete($tourismFacility);

        return redirect()->route('admin.tourism-facilities.index')
            ->with('success', 'Fasilitas wisata berhasil dihapus.');
    }
}

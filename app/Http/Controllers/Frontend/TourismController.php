<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourismCategoryResource;
use App\Http\Resources\TourismFacilityResource;
use App\Http\Resources\TourismResource;
use App\Models\Tourism;
use App\Services\TourismCategoryService;
use App\Services\TourismFacilityService;
use App\Services\TourismService;
use Illuminate\Http\Request;
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
     * Display a listing of the resource for public.
     */
    public function index(Request $request): Response
    {
        $tourisms = $this->service->getPublicPaginated(
            filters: $request->only(['cari', 'kategori', 'fasilitas']),
            perPage: 9
        );

        $categories = $this->categoryService->getAll();
        $facilities = $this->facilityService->getAll();

        return Inertia::render('Frontend/Tourism/Index', [
            'tourisms' => TourismResource::collection($tourisms),
            'categories' => TourismCategoryResource::collection($categories),
            'facilities' => TourismFacilityResource::collection($facilities),
            'filters' => $request->only(['cari', 'kategori', 'fasilitas']),
        ]);
    }

    /**
     * Display the specified resource for public.
     */
    public function show(string $slug): Response
    {
        $tourism = Tourism::query()
            ->published()
            ->withDetails()
            ->where('slug', $slug)
            ->firstOrFail();

        $this->service->incrementViewCount($tourism);

        // Fetch nearby locations
        $nearby = collect();
        if ($tourism->latitude && $tourism->longitude) {
            $nearby = Tourism::query()
                ->published()
                ->with(['category', 'photos'])
                ->nearby((float) $tourism->latitude, (float) $tourism->longitude, 50) // 50km radius roughly
                ->where('id', '!=', $tourism->id)
                ->limit(3)
                ->get();
        } else {
            // Fallback to related by category
            $nearby = Tourism::query()
                ->published()
                ->with(['category', 'photos'])
                ->where('category_id', $tourism->category_id)
                ->where('id', '!=', $tourism->id)
                ->limit(3)
                ->get();
        }

        return Inertia::render('Frontend/Tourism/Show', [
            'tourism' => new TourismResource($tourism),
            'nearby' => TourismResource::collection($nearby),
        ]);
    }
}

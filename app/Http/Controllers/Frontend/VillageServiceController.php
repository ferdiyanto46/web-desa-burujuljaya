<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\VillageServiceResource;
use App\Services\VillageServiceService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VillageServiceController extends Controller
{
    public function __construct(
        private readonly VillageServiceService $service
    ) {}

    public function index(Request $request): Response
    {
        $services = $this->service->getPaginatedForPublic($request->query('search'));

        return Inertia::render('Public/Services/Index', [
            'villageServices' => VillageServiceResource::collection($services),
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(string $slug): Response
    {
        $villageService = $this->service->getBySlug($slug);

        $villageService->load('author');

        return Inertia::render('Public/Services/Show', [
            'villageService' => new VillageServiceResource($villageService),
        ]);
    }
}

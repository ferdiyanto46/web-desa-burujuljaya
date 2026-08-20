<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\VillagePageResource;
use App\Models\Apparatus;
use App\Models\VillagePage;
use App\Models\VillageProfile;
use App\Services\VillagePageService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VillagePageController extends Controller
{
    public function __construct(
        private readonly VillagePageService $service
    ) {}

    public function index(Request $request): Response
    {
        $pages = $this->service->getPaginatedForPublic(
            $request->query('search')
        );

        $villageProfile = VillageProfile::with('missions')->first();

        $apparatuses = Apparatus::query()
            ->where('is_active', true)
            ->with('position')
            ->join('apparatus_positions', 'apparatus.position_id', '=', 'apparatus_positions.id')
            ->orderBy('apparatus_positions.order', 'asc')
            ->orderBy('apparatus.id', 'asc')
            ->select('apparatus.*')
            ->get();

        return Inertia::render('Public/Profile/Index', [
            'pages' => VillagePageResource::collection($pages),
            'filters' => $request->only(['search']),
            'villageProfile' => $villageProfile,
            'apparatuses' => $apparatuses,
        ]);
    }

    public function show(string $slug): Response
    {
        $page = VillagePage::query()
            ->published()
            ->withDetails()
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Public/Profile/Show', [
            'page' => new VillagePageResource($page),
        ]);
    }
}

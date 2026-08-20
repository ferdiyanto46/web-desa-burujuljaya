<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVillageServiceRequest;
use App\Http\Requests\Admin\UpdateVillageServiceRequest;
use App\Http\Resources\VillageServiceResource;
use App\Models\VillageService;
use App\Services\VillageServiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class VillageServiceController extends Controller
{
    public function __construct(
        private readonly VillageServiceService $service
    ) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', VillageService::class);

        $services = $this->service->getPaginatedForAdmin($request->query('search'));

        return Inertia::render('Admin/VillageServices/Index', [
            'villageServices' => VillageServiceResource::collection($services),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', VillageService::class);

        return Inertia::render('Admin/VillageServices/Create', [
            'statuses' => PublicationStatus::cases(),
        ]);
    }

    public function store(StoreVillageServiceRequest $request): RedirectResponse
    {
        Gate::authorize('create', VillageService::class);

        $this->service->store($request->validated(), $request->user()->id);

        return redirect()->route('admin.village-services.index')
            ->with('success', 'Village service created successfully.');
    }

    public function show(VillageService $villageService): Response
    {
        Gate::authorize('view', $villageService);

        $villageService->load('author');

        return Inertia::render('Admin/VillageServices/Show', [
            'villageService' => new VillageServiceResource($villageService),
        ]);
    }

    public function edit(VillageService $villageService): Response
    {
        Gate::authorize('update', $villageService);

        $villageService->load('author');

        return Inertia::render('Admin/VillageServices/Edit', [
            'villageService' => new VillageServiceResource($villageService),
            'statuses' => PublicationStatus::cases(),
        ]);
    }

    public function update(UpdateVillageServiceRequest $request, VillageService $villageService): RedirectResponse
    {
        Gate::authorize('update', $villageService);

        $this->service->update($villageService, $request->validated());

        return redirect()->route('admin.village-services.index')
            ->with('success', 'Village service updated successfully.');
    }

    public function destroy(VillageService $villageService): RedirectResponse
    {
        Gate::authorize('delete', $villageService);

        $this->service->delete($villageService);

        return redirect()->route('admin.village-services.index')
            ->with('success', 'Village service deleted successfully.');
    }
}

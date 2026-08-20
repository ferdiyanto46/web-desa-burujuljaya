<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublicationStatus;
use App\Enums\VillagePageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVillagePageRequest;
use App\Http\Requests\Admin\UpdateVillagePageRequest;
use App\Http\Resources\VillagePageResource;
use App\Models\VillagePage;
use App\Services\VillagePageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class VillagePageController extends Controller
{
    public function __construct(
        private readonly VillagePageService $service
    ) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', VillagePage::class);

        $pages = $this->service->getPaginatedForAdmin(
            $request->query('search')
        );

        return Inertia::render('Admin/VillagePages/Index', [
            'pages' => VillagePageResource::collection($pages),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', VillagePage::class);

        return Inertia::render('Admin/VillagePages/Create', [
            'statuses' => PublicationStatus::cases(),
            'pageTypes' => VillagePageType::cases(),
        ]);
    }

    public function store(StoreVillagePageRequest $request): RedirectResponse
    {
        Gate::authorize('create', VillagePage::class);

        $this->service->store($request->validated(), $request->user()->id);

        return redirect()->route('admin.village-pages.index')
            ->with('success', 'Halaman desa berhasil ditambahkan.');
    }

    public function show(VillagePage $villagePage): Response
    {
        Gate::authorize('view', $villagePage);

        return Inertia::render('Admin/VillagePages/Show', [
            'page' => new VillagePageResource($villagePage->load('author')),
        ]);
    }

    public function edit(VillagePage $villagePage): Response
    {
        Gate::authorize('update', $villagePage);

        return Inertia::render('Admin/VillagePages/Edit', [
            'page' => new VillagePageResource($villagePage),
            'statuses' => PublicationStatus::cases(),
            'pageTypes' => VillagePageType::cases(),
        ]);
    }

    public function update(UpdateVillagePageRequest $request, VillagePage $villagePage): RedirectResponse
    {
        Gate::authorize('update', $villagePage);

        $this->service->update($villagePage, $request->validated());

        return redirect()->route('admin.village-pages.index')
            ->with('success', 'Halaman desa berhasil diperbarui.');
    }

    public function destroy(VillagePage $villagePage): RedirectResponse
    {
        Gate::authorize('delete', $villagePage);

        $this->service->delete($villagePage);

        return redirect()->route('admin.village-pages.index')
            ->with('success', 'Halaman desa berhasil dihapus.');
    }

    public function restore(VillagePage $villagePage): RedirectResponse
    {
        Gate::authorize('restore', $villagePage);

        $this->service->restore($villagePage);

        return redirect()->route('admin.village-pages.index')
            ->with('success', 'Halaman desa berhasil dipulihkan.');
    }

    public function forceDelete(VillagePage $villagePage): RedirectResponse
    {
        Gate::authorize('forceDelete', $villagePage);

        $this->service->forceDelete($villagePage);

        return redirect()->route('admin.village-pages.index')
            ->with('success', 'Halaman desa berhasil dihapus permanen.');
    }
}

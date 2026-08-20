<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUmkmRequest;
use App\Http\Requests\Admin\UpdateUmkmRequest;
use App\Http\Resources\UmkmCategoryResource;
use App\Http\Resources\UmkmResource;
use App\Models\Umkm;
use App\Models\UmkmCategory;
use App\Services\UmkmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UmkmController extends Controller
{
    public function __construct(
        private readonly UmkmService $umkmService,
    ) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', Umkm::class);

        $umkms = Umkm::query()->with('category')->latest()->paginate(10);

        return Inertia::render('Admin/Umkm/Index', [
            'umkms' => UmkmResource::collection($umkms),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Umkm::class);

        return Inertia::render('Admin/Umkm/Create', [
            'categories' => UmkmCategoryResource::collection(UmkmCategory::all()),
        ]);
    }

    public function store(StoreUmkmRequest $request): RedirectResponse
    {
        Gate::authorize('create', Umkm::class);

        $this->umkmService->store($request->validated(), $request->file('featured_image'), $request->file('gallery') ?? []);

        return redirect()->route('admin.umkm.index')
            ->with('success', 'UMKM created successfully.');
    }

    public function show(Umkm $umkm): Response
    {
        Gate::authorize('view', $umkm);

        $umkm->load('category', 'photos');

        return Inertia::render('Admin/Umkm/Show', [
            'umkm' => new UmkmResource($umkm),
        ]);
    }

    public function edit(Umkm $umkm): Response
    {
        Gate::authorize('update', $umkm);

        $umkm->load('photos');

        return Inertia::render('Admin/Umkm/Edit', [
            'umkm' => new UmkmResource($umkm),
            'categories' => UmkmCategoryResource::collection(UmkmCategory::all()),
        ]);
    }

    public function update(UpdateUmkmRequest $request, Umkm $umkm): RedirectResponse
    {
        Gate::authorize('update', $umkm);

        $this->umkmService->update($umkm, $request->validated(), $request->file('featured_image'), $request->file('gallery') ?? []);

        return redirect()->route('admin.umkm.index')
            ->with('success', 'UMKM updated successfully.');
    }

    public function destroy(Umkm $umkm): RedirectResponse
    {
        Gate::authorize('delete', $umkm);

        $this->umkmService->delete($umkm);

        return redirect()->route('admin.umkm.index')
            ->with('success', 'UMKM deleted successfully.');
    }

    public function restore(Umkm $umkm): RedirectResponse
    {
        Gate::authorize('restore', $umkm);

        $this->umkmService->restore($umkm);

        return redirect()->route('admin.umkm.index')
            ->with('success', 'UMKM restored successfully.');
    }

    public function forceDelete(Umkm $umkm): RedirectResponse
    {
        Gate::authorize('forceDelete', $umkm);

        $this->umkmService->forceDelete($umkm);

        return redirect()->route('admin.umkm.index')
            ->with('success', 'UMKM permanently deleted.');
    }
}

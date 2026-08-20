<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUmkmCategoryRequest;
use App\Http\Requests\Admin\UpdateUmkmCategoryRequest;
use App\Http\Resources\UmkmCategoryResource;
use App\Models\UmkmCategory;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UmkmCategoryController extends Controller
{
    public function index(): Response
    {
        $categories = UmkmCategory::query()->latest()->paginate(10);

        return Inertia::render('Admin/Umkm/Category/Index', [
            'categories' => UmkmCategoryResource::collection($categories),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Umkm/Category/Create');
    }

    public function store(StoreUmkmCategoryRequest $request): RedirectResponse
    {
        UmkmCategory::create($request->validated());

        return redirect()->route('admin.umkm-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(UmkmCategory $umkmCategory): Response
    {
        return Inertia::render('Admin/Umkm/Category/Show', [
            'category' => new UmkmCategoryResource($umkmCategory),
        ]);
    }

    public function edit(UmkmCategory $umkmCategory): Response
    {
        return Inertia::render('Admin/Umkm/Category/Edit', [
            'category' => new UmkmCategoryResource($umkmCategory),
        ]);
    }

    public function update(UpdateUmkmCategoryRequest $request, UmkmCategory $umkmCategory): RedirectResponse
    {
        $umkmCategory->update($request->validated());

        return redirect()->route('admin.umkm-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(UmkmCategory $umkmCategory): RedirectResponse
    {
        $umkmCategory->delete();

        return redirect()->route('admin.umkm-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}

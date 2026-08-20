<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Services\HomepageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class HomepageSectionController extends Controller
{
    public function __construct(private HomepageService $homepageService) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', HomepageSection::class);

        $sections = $this->homepageService->getSectionsForAdmin();

        return Inertia::render('Admin/Homepage/Index', [
            'sections' => $sections,
        ]);
    }

    public function edit(HomepageSection $homepageSection): Response
    {
        Gate::authorize('update', $homepageSection);

        return Inertia::render('Admin/Homepage/Edit', [
            'section' => $homepageSection,
        ]);
    }

    public function update(Request $request, HomepageSection $homepageSection): RedirectResponse
    {
        Gate::authorize('update', $homepageSection);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'configuration' => 'nullable|array',
            'banner_image' => 'nullable|image|max:5120',
            'is_enabled' => 'boolean',
        ]);

        $bannerImage = $request->file('banner_image');

        $this->homepageService->updateSection($homepageSection, $validated, $bannerImage);

        return redirect()->route('admin.homepage-sections.index')
            ->with('success', 'Bagian beranda berhasil diperbarui.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        Gate::authorize('updateAny', HomepageSection::class);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        $this->homepageService->reorderSections($validated['order']);

        return back()->with('success', 'Urutan berhasil diperbarui.');
    }

    public function toggle(HomepageSection $homepageSection): RedirectResponse
    {
        Gate::authorize('update', $homepageSection);

        $this->homepageService->toggleSection($homepageSection);

        return back()->with('success', 'Status berhasil diubah.');
    }
}

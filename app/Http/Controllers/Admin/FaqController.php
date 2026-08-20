<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Services\FaqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class FaqController extends Controller
{
    public function __construct(
        private readonly FaqService $faqService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Faq::class);
        $faqs = $this->faqService->getPaginatedForAdmin($request->all());

        return Inertia::render('Admin/Faq/Index', [
            'faqs' => FaqResource::collection($faqs),
            'filters' => $request->only(['search', 'status', 'is_featured']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Faq::class);

        return Inertia::render('Admin/Faq/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaqRequest $request): RedirectResponse
    {
        Gate::authorize('create', Faq::class);
        $this->faqService->store($request->validated());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq): Response
    {
        Gate::authorize('view', $faq);
        $faq->load(['author']);

        return Inertia::render('Admin/Faq/Show', [
            'faq' => new FaqResource($faq),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq): Response
    {
        Gate::authorize('update', $faq);

        return Inertia::render('Admin/Faq/Edit', [
            'faq' => new FaqResource($faq),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaqRequest $request, Faq $faq): RedirectResponse
    {
        Gate::authorize('update', $faq);
        $this->faqService->update($faq->ulid, $request->validated());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq): RedirectResponse
    {
        Gate::authorize('delete', $faq);
        $this->faqService->delete($faq->ulid);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil dihapus.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, Faq $faq): RedirectResponse
    {
        Gate::authorize('restore', $faq);

        $this->faqService->restore($faq->ulid);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil dipulihkan.');
    }

    /**
     * Permanently remove the specified resource from storage.
     */
    public function forceDelete(Request $request, Faq $faq): RedirectResponse
    {
        Gate::authorize('forceDelete', $faq);

        $this->faqService->forceDelete($faq->ulid);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil dihapus permanen.');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Http\Requests\Admin\UpdateDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentService $documentService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Document::class);

        $filters = $request->only(['search', 'category_id', 'status', 'is_pinned']);
        $documents = $this->documentService->getAdminPaginated($filters);

        return Inertia::render('Admin/Documents/Index', [
            'documents' => DocumentResource::collection($documents),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Document::class);

        return Inertia::render('Admin/Documents/Create', [
            'categories' => DocumentCategory::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $this->documentService->create($request->validated(), $request->user());

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): Response
    {
        Gate::authorize('update', $document);

        return Inertia::render('Admin/Documents/Edit', [
            'document' => new DocumentResource($document->load('category')),
            'categories' => DocumentCategory::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $this->documentService->update($document, $request->validated());

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        Gate::authorize('delete', $document);

        $this->documentService->delete($document);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Document $document): RedirectResponse
    {
        Gate::authorize('restore', $document);

        $this->documentService->restore($document);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dipulihkan.');
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(Document $document): RedirectResponse
    {
        Gate::authorize('forceDelete', $document);

        $this->documentService->forceDelete($document);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus permanen.');
    }
}

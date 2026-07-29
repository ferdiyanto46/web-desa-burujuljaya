<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Http\Requests\Admin\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentService $documentService
    ) {}

    /**
     * Display a listing of the documents.
     */
    public function index(): Response
    {
        $documents = Document::withDetails()->latest()->paginate(15);

        return Inertia::render('Admin/Documents/Index', [
            'documents' => $documents,
        ]);
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(): Response
    {
        $categories = DocumentCategory::all();

        return Inertia::render('Admin/Documents/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile $file */
        $file = $request->file('file');

        $this->documentService->upload($data, $file);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document): Response
    {
        $categories = DocumentCategory::all();

        return Inertia::render('Admin/Documents/Edit', [
            'document' => $document,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified document in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $file */
        $file = $request->file('file');

        $this->documentService->update($document, $data, $file);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        $this->documentService->remove($document);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}

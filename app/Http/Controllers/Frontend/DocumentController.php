<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentService $documentService
    ) {}

    /**
     * Display a listing of public documents.
     */
    public function index(Request $request): Response
    {
        $query = Document::withDetails()->public()->latest();

        if ($request->has('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->string('kategori')->toString());
            });
        }

        if ($request->has('cari')) {
            $query->where('title', 'like', '%'.$request->string('cari')->toString().'%');
        }

        $documents = $query->paginate(15)->withQueryString();
        $categories = DocumentCategory::all();

        return Inertia::render('Public/Documents/Index', [
            'documents' => $documents,
            'categories' => $categories,
            'filters' => $request->only(['kategori', 'cari']),
        ]);
    }

    /**
     * Download the specified document.
     */
    public function download(string $ulid): StreamedResponse
    {
        $document = Document::public()->where('ulid', $ulid)->firstOrFail();

        $this->documentService->incrementDownload($document);

        return Storage::disk('public')->download(
            $document->file_path,
            $document->title.'.'.$document->file_extension
        );
    }
}

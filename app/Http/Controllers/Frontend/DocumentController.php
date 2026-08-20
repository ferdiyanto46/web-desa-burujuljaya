<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $filters = $request->only(['cari', 'kategori', 'search', 'category_id']);

        $documents = $this->documentService->getPublicPaginated($filters);
        $categories = DocumentCategory::select('id', 'name', 'slug')->get();

        $frontendFilters = [
            'cari' => (string) ($request->input('cari') ?? $request->input('search') ?? ''),
            'kategori' => (string) ($request->input('kategori') ?? $request->input('category_id') ?? ''),
        ];

        return Inertia::render('Public/Documents/Index', [
            'documents' => DocumentResource::collection($documents),
            'categories' => $categories,
            'filters' => $frontendFilters,
        ]);
    }

    /**
     * Download the specified document.
     */
    public function download(string $ulid): StreamedResponse
    {
        $document = Document::where('ulid', $ulid)->published()->firstOrFail();

        $this->documentService->incrementDownloadCounter($document);

        return Storage::disk($document->disk)->download(
            $document->file_path,
            $document->file_name
        );
    }
}

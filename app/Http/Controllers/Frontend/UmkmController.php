<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\UmkmResource;
use App\Models\Umkm;
use App\Models\UmkmCategory;
use App\Services\UmkmService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UmkmController extends Controller
{
    public function __construct(
        private readonly UmkmService $umkmService,
    ) {}

    public function index(Request $request): Response
    {
        $categorySlug = $request->string('kategori')->trim()->toString();
        $categoryId = null;
        if ($categorySlug !== '' && $categorySlug !== 'all' && $categorySlug !== 'semua') {
            $category = UmkmCategory::where('slug', $categorySlug)->first();
            $categoryId = $category?->id;
        }

        $search = $request->string('cari')->trim()->toString();
        if ($search === '') {
            $search = $request->string('search')->trim()->toString();
        }

        $umkms = $this->umkmService->getPublicPaginated(
            12,
            $categoryId,
            $search
        );

        return Inertia::render('Frontend/Umkm/Index', [
            'umkms' => UmkmResource::collection($umkms),
            'filters' => [
                'cari' => $search,
                'kategori' => $categorySlug,
            ],
            'categories' => UmkmCategory::all(),
        ]);
    }

    public function show(Umkm $umkm): Response
    {
        if ($umkm->status !== PublicationStatus::Published && ! auth()->check()) {
            abort(404);
        }

        $this->umkmService->incrementViewCount($umkm);

        return Inertia::render('Frontend/Umkm/Show', [
            'umkm' => new UmkmResource($umkm->load(['category', 'photos'])),
        ]);
    }
}

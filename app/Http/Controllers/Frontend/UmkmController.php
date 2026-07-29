<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\UmkmCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class UmkmController extends Controller
{
    /**
     * Display a listing of UMKM.
     */
    public function index(Request $request): Response
    {
        $query = Umkm::withDetails()->where('is_active', true)->latest();

        if ($request->has('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->string('kategori')->toString());
            });
        }

        if ($request->has('cari')) {
            $query->where('name', 'like', '%'.$request->string('cari')->toString().'%');
        }

        $umkms = $query->paginate(9)->withQueryString();
        $categories = UmkmCategory::all();

        return Inertia::render('Public/Umkm/Index', [
            'umkms' => $umkms,
            'categories' => $categories,
            'filters' => $request->only(['kategori', 'cari']),
        ]);
    }

    /**
     * Display the specified UMKM and its products.
     */
    public function show(string $slug): Response
    {
        $umkm = Umkm::withDetails()
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $umkm->load(['products' => function ($query) {
            $query->where('is_active', true)->latest();
        }]);

        return Inertia::render('Public/Umkm/Show', [
            'umkm' => $umkm,
        ]);
    }
}

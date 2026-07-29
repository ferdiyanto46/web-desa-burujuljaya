<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tourism;
use App\Models\TourismCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class TourismController extends Controller
{
    /**
     * Display a listing of tourism spots.
     */
    public function index(Request $request): Response
    {
        $query = Tourism::withDetails()->where('is_active', true)->latest();

        if ($request->has('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->string('kategori')->toString());
            });
        }

        if ($request->has('cari')) {
            $query->where('name', 'like', '%'.$request->string('cari')->toString().'%');
        }

        $tourisms = $query->paginate(9)->withQueryString();
        $categories = TourismCategory::all();

        return Inertia::render('Public/Tourism/Index', [
            'tourisms' => $tourisms,
            'categories' => $categories,
            'filters' => $request->only(['kategori', 'cari']),
        ]);
    }

    /**
     * Display the specified tourism spot.
     */
    public function show(string $slug): Response
    {
        $tourism = Tourism::withDetails()
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Public/Tourism/Show', [
            'tourism' => $tourism,
        ]);
    }
}

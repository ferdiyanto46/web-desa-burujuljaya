<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): Response
    {
        $query = Product::withDetails()->where('is_active', true)->latest();

        if ($request->has('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->string('kategori')->toString());
            });
        }

        if ($request->has('cari')) {
            $query->where('name', 'like', '%'.$request->string('cari')->toString().'%');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = ProductCategory::all();

        return Inertia::render('Public/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['kategori', 'cari']),
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug): Response
    {
        $product = Product::withDetails()
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Public/Products/Show', [
            'product' => $product,
        ]);
    }
}

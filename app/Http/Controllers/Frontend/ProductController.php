<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\PublicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(Request $request): Response
    {
        $query = Product::where('status', PublicationStatus::Published);

        if ($request->filled('kategori')) {
            $slug = $request->kategori;
            $category = ProductCategory::where('slug', $slug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('cari')) {
            $query->where('name', 'like', '%'.$request->cari.'%');
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('Frontend/Products/Index', [
            'products' => ProductResource::collection($products),
            'categories' => ProductCategoryResource::collection(ProductCategory::all()),
            'filters' => $request->only(['cari', 'kategori']),
        ]);
    }

    public function show(Product $product): Response
    {
        if ($product->status !== PublicationStatus::Published) {
            abort(404);
        }

        $this->productService->incrementViewCount($product);

        return Inertia::render('Frontend/Products/Show', [
            'product' => new ProductResource($product->load(['category', 'author', 'umkm', 'photos'])),
        ]);
    }
}

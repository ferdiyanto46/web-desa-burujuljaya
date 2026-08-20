<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UmkmResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Umkm;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Product::class);

        $products = $this->productService->getPaginated(
            15,
            $request->query('search', ''),
            $request->query('category_id')
        );

        return Inertia::render('Admin/Products/Index', [
            'products' => ProductResource::collection($products),
            'filters' => $request->only(['search', 'category_id']),
            'categories' => ProductCategoryResource::collection(ProductCategory::all()),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Product::class);

        return Inertia::render('Admin/Products/Create', [
            'categories' => ProductCategoryResource::collection(ProductCategory::all()),
            'umkms' => UmkmResource::collection(Umkm::all()),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        $this->productService->store(
            $request->validated(),
            $request->file('featured_image'),
            $request->file('gallery', [])
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): Response
    {
        Gate::authorize('view', $product);

        $product->load(['category', 'author', 'umkm', 'photos']);

        return Inertia::render('Admin/Products/Show', [
            'product' => new ProductResource($product),
        ]);
    }

    public function edit(Product $product): Response
    {
        Gate::authorize('update', $product);

        $product->load(['category', 'umkm', 'photos']);

        return Inertia::render('Admin/Products/Edit', [
            'product' => new ProductResource($product),
            'categories' => ProductCategoryResource::collection(ProductCategory::all()),
            'umkms' => UmkmResource::collection(Umkm::all()),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $this->productService->update(
            $product,
            $request->validated(),
            $request->file('featured_image'),
            $request->file('gallery', [])
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        $this->productService->delete($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function restore(Product $product): RedirectResponse
    {
        Gate::authorize('restore', $product);

        $this->productService->restore($product);

        return redirect()->back()
            ->with('success', 'Produk berhasil dipulihkan.');
    }

    public function forceDelete(Product $product): RedirectResponse
    {
        Gate::authorize('forceDelete', $product);

        $this->productService->forceDelete($product);

        return redirect()->back()
            ->with('success', 'Produk dihapus permanen.');
    }
}

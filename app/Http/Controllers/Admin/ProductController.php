<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Umkm;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    /**
     * Display a listing of the products.
     */
    public function index(): Response
    {
        $products = Product::withDetails()->latest()->paginate(15);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        $categories = ProductCategory::all();
        $umkms = Umkm::all();

        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories,
            'umkms' => $umkms,
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $coverImage */
        $coverImage = $request->file('cover_image');

        $this->productService->create($data, $coverImage);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): Response
    {
        $categories = ProductCategory::all();
        $umkms = Umkm::all();

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'umkms' => $umkms,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $coverImage */
        $coverImage = $request->file('cover_image');

        $this->productService->update($product, $data, $coverImage);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->remove($product);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}

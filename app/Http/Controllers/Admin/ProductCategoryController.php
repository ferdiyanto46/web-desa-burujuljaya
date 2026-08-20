<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductCategoryController extends Controller
{
    public function index(): Response
    {
        $categories = ProductCategory::query()->latest()->paginate(10);

        return Inertia::render('Admin/Products/Category/Index', [
            'categories' => $categories,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Category/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ProductCategory::create($validated);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    public function edit(ProductCategory $productCategory): Response
    {
        return Inertia::render('Admin/Products/Category/Edit', [
            'category' => $productCategory,
        ]);
    }

    public function update(Request $request, ProductCategory $productCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $productCategory->update($validated);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->delete();

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori produk berhasil dihapus.');
    }
}

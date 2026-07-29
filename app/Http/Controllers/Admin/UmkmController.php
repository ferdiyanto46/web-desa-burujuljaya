<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUmkmRequest;
use App\Http\Requests\Admin\UpdateUmkmRequest;
use App\Models\Umkm;
use App\Models\UmkmCategory;
use App\Services\UmkmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class UmkmController extends Controller
{
    public function __construct(
        private readonly UmkmService $umkmService
    ) {}

    /**
     * Display a listing of the UMKM.
     */
    public function index(): Response
    {
        $umkms = Umkm::withDetails()->latest()->paginate(15);

        return Inertia::render('Admin/Umkm/Index', [
            'umkms' => $umkms,
        ]);
    }

    /**
     * Show the form for creating a new UMKM.
     */
    public function create(): Response
    {
        $categories = UmkmCategory::all();

        return Inertia::render('Admin/Umkm/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created UMKM in storage.
     */
    public function store(StoreUmkmRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $coverImage */
        $coverImage = $request->file('cover_image');

        $this->umkmService->create($data, $coverImage);

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified UMKM.
     */
    public function edit(Umkm $umkm): Response
    {
        $categories = UmkmCategory::all();

        return Inertia::render('Admin/Umkm/Edit', [
            'umkm' => $umkm,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified UMKM in storage.
     */
    public function update(UpdateUmkmRequest $request, Umkm $umkm): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $coverImage */
        $coverImage = $request->file('cover_image');

        $this->umkmService->update($umkm, $data, $coverImage);

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil diperbarui.');
    }

    /**
     * Remove the specified UMKM from storage.
     */
    public function destroy(Umkm $umkm): RedirectResponse
    {
        $this->umkmService->remove($umkm);

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil dihapus.');
    }
}

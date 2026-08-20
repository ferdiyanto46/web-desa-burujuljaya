<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApparatusRequest;
use App\Http\Requests\Admin\UpdateApparatusRequest;
use App\Models\Apparatus;
use App\Models\ApparatusPosition;
use App\Services\ApparatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class ApparatusController extends Controller
{
    public function __construct(
        private readonly ApparatusService $apparatusService
    ) {}

    /**
     * Display a listing of the apparatus.
     */
    public function index(Request $request): Response
    {
        $query = Apparatus::withDetails();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('education', 'like', "%{$search}%");
            });
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->integer('position_id'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $apparatuses = $query->latest()->paginate(10)->withQueryString();
        $positions = ApparatusPosition::withCount('apparatuses')->orderBy('order')->get();

        return Inertia::render('Admin/Apparatus/Index', [
            'apparatuses' => $apparatuses,
            'positions' => $positions,
            'filters' => $request->only(['search', 'position_id', 'is_active']),
        ]);
    }

    /**
     * Show the form for creating a new apparatus.
     */
    public function create(): Response
    {
        $positions = ApparatusPosition::withCount('apparatuses')->orderBy('order')->get();

        return Inertia::render('Admin/Apparatus/Create', [
            'positions' => $positions,
        ]);
    }

    /**
     * Store a newly created apparatus in storage.
     */
    public function store(StoreApparatusRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $photo */
        $photo = $request->file('photo');

        $this->apparatusService->appoint($data, $photo);

        return redirect()->route('admin.apparatus.index')->with('success', 'Perangkat desa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified apparatus.
     */
    public function edit(Apparatus $apparatus): Response
    {
        $apparatus->load('position');
        $positions = ApparatusPosition::withCount('apparatuses')->orderBy('order')->get();

        return Inertia::render('Admin/Apparatus/Edit', [
            'apparatus' => $apparatus,
            'positions' => $positions,
        ]);
    }

    /**
     * Update the specified apparatus in storage.
     */
    public function update(UpdateApparatusRequest $request, Apparatus $apparatus): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $photo */
        $photo = $request->file('photo');

        $this->apparatusService->update($apparatus, $data, $photo);

        return redirect()->route('admin.apparatus.index')->with('success', 'Data perangkat desa berhasil diperbarui.');
    }

    /**
     * Remove the specified apparatus from storage.
     */
    public function destroy(Apparatus $apparatus): RedirectResponse
    {
        $this->apparatusService->remove($apparatus);

        return redirect()->route('admin.apparatus.index')->with('success', 'Perangkat desa berhasil dihapus.');
    }
}

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
    public function index(): Response
    {
        $apparatuses = Apparatus::withDetails()->latest()->paginate(15);

        return Inertia::render('Admin/Apparatus/Index', [
            'apparatuses' => $apparatuses,
        ]);
    }

    /**
     * Show the form for creating a new apparatus.
     */
    public function create(): Response
    {
        $positions = ApparatusPosition::all();

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

        return redirect()->route('admin.apparatus.index')->with('success', 'Aparatur berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified apparatus.
     */
    public function edit(Apparatus $apparatus): Response
    {
        $positions = ApparatusPosition::all();

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

        return redirect()->route('admin.apparatus.index')->with('success', 'Aparatur berhasil diperbarui.');
    }

    /**
     * Remove the specified apparatus from storage.
     */
    public function destroy(Apparatus $apparatus): RedirectResponse
    {
        $this->apparatusService->remove($apparatus);

        return redirect()->route('admin.apparatus.index')->with('success', 'Aparatur berhasil dihapus.');
    }
}

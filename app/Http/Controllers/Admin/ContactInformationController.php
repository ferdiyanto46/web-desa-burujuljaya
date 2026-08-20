<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactInformationRequest;
use App\Http\Requests\UpdateContactInformationRequest;
use App\Http\Resources\ContactInformationResource;
use App\Models\ContactInformation;
use App\Services\ContactInformationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ContactInformationController extends Controller
{
    public function __construct(
        private readonly ContactInformationService $contactInformationService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', ContactInformation::class);
        $contactInformations = $this->contactInformationService->getPaginatedForAdmin($request->all());

        return Inertia::render('Admin/ContactInformation/Index', [
            'contactInformations' => ContactInformationResource::collection($contactInformations),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', ContactInformation::class);

        return Inertia::render('Admin/ContactInformation/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactInformationRequest $request): RedirectResponse
    {
        Gate::authorize('create', ContactInformation::class);
        $this->contactInformationService->store($request->validated());

        return redirect()->route('admin.contact-information.index')
            ->with('success', 'Informasi Kontak berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactInformation $contactInformation): Response
    {
        Gate::authorize('view', $contactInformation);
        $contactInformation->load(['author']);

        return Inertia::render('Admin/ContactInformation/Show', [
            'contactInformation' => new ContactInformationResource($contactInformation),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactInformation $contactInformation): Response
    {
        Gate::authorize('update', $contactInformation);

        return Inertia::render('Admin/ContactInformation/Edit', [
            'contactInformation' => new ContactInformationResource($contactInformation),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactInformationRequest $request, ContactInformation $contactInformation): RedirectResponse
    {
        Gate::authorize('update', $contactInformation);
        $this->contactInformationService->update($contactInformation->ulid, $request->validated());

        return redirect()->route('admin.contact-information.index')
            ->with('success', 'Informasi Kontak berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactInformation $contactInformation): RedirectResponse
    {
        Gate::authorize('delete', $contactInformation);
        $this->contactInformationService->delete($contactInformation->ulid);

        return redirect()->route('admin.contact-information.index')
            ->with('success', 'Informasi Kontak berhasil dihapus.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, ContactInformation $contactInformation): RedirectResponse
    {
        Gate::authorize('restore', $contactInformation);

        $this->contactInformationService->restore($contactInformation->ulid);

        return redirect()->route('admin.contact-information.index')
            ->with('success', 'Informasi Kontak berhasil dipulihkan.');
    }

    /**
     * Permanently remove the specified resource from storage.
     */
    public function forceDelete(Request $request, ContactInformation $contactInformation): RedirectResponse
    {
        Gate::authorize('forceDelete', $contactInformation);

        $this->contactInformationService->forceDelete($contactInformation->ulid);

        return redirect()->route('admin.contact-information.index')
            ->with('success', 'Informasi Kontak berhasil dihapus permanen.');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAgendaRequest;
use App\Http\Requests\Admin\UpdateAgendaRequest;
use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use App\Services\AgendaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class AgendaController extends Controller
{
    public function __construct(
        private readonly AgendaService $agendaService
    ) {}

    /**
     * Display a listing of the agendas.
     */
    public function index(Request $request): Response
    {
        \Illuminate\Support\Facades\Gate::authorize('viewAny', Agenda::class);

        $query = Agenda::withDetails()->latest('start_datetime');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $agendas = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Agendas/Index', [
            'agendas' => AgendaResource::collection($agendas),
        ]);
    }

    /**
     * Show the form for creating a new agenda.
     */
    public function create(): Response
    {
        \Illuminate\Support\Facades\Gate::authorize('create', Agenda::class);

        return Inertia::render('Admin/Agendas/Create');
    }

    /**
     * Store a newly created agenda in storage.
     */
    public function store(StoreAgendaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $coverImage = $request->file('featured_image');

        $this->agendaService->schedule($data, $coverImage);

        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified agenda.
     */
    public function edit(Agenda $agenda): Response
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $agenda);

        return Inertia::render('Admin/Agendas/Edit', [
            'agenda' => new AgendaResource($agenda),
        ]);
    }

    /**
     * Update the specified agenda in storage.
     */
    public function update(UpdateAgendaRequest $request, Agenda $agenda): RedirectResponse
    {
        $data = $request->validated();
        $coverImage = $request->file('featured_image');

        $this->agendaService->update($agenda, $data, $coverImage);

        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Remove the specified agenda from storage.
     */
    public function destroy(Agenda $agenda): RedirectResponse
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $agenda);

        $this->agendaService->cancel($agenda);

        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil dihapus.');
    }
}

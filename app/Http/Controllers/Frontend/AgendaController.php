<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Inertia\Inertia;
use Inertia\Response;

final class AgendaController extends Controller
{
    /**
     * Display a listing of upcoming agendas.
     */
    public function index(): Response
    {
        $agendas = Agenda::withDetails()
            ->upcoming()
            ->paginate(10);

        return Inertia::render('Public/Agendas/Index', [
            'agendas' => $agendas,
        ]);
    }

    /**
     * Display the specified agenda.
     */
    public function show(string $slug): Response
    {
        // For show, we might want to allow viewing past agendas too,
        // so we just check if it's published.
        $agenda = Agenda::withDetails()
            ->where('slug', $slug)
            ->where('status', ArticleStatus::Published)
            ->firstOrFail();

        return Inertia::render('Public/Agendas/Show', [
            'agenda' => $agenda,
        ]);
    }
}

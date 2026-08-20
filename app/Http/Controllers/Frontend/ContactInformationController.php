<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactInformationResource;
use App\Services\ContactInformationService;
use Illuminate\Http\Request;
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
        $contactInformations = $this->contactInformationService->getPaginatedForPublic($request->all());

        return Inertia::render('Public/ContactInformation/Index', [
            'contactInformations' => ContactInformationResource::collection($contactInformations),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug): Response
    {
        $contactInformation = $this->contactInformationService->getBySlug($slug);

        return Inertia::render('Public/ContactInformation/Show', [
            'contactInformation' => new ContactInformationResource($contactInformation),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\HomepageService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    public function __construct(private HomepageService $homepageService) {}

    /**
     * Display the dynamic landing page.
     */
    public function index(Request $request): Response
    {
        $sections = $this->homepageService->getPublicHomepage();

        return Inertia::render('Public/Home/Index', [
            'sections' => $sections,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Services\FaqService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FaqController extends Controller
{
    public function __construct(
        private readonly FaqService $faqService
    ) {}

    /**
     * Display a listing of the published FAQs.
     */
    public function index(Request $request): Response
    {
        $faqs = $this->faqService->getPaginatedForPublic($request->all(), 12);
        $categories = Faq::published()->select('category')->distinct()->whereNotNull('category')->pluck('category');

        return Inertia::render('Public/Faq/Index', [
            'faqs' => FaqResource::collection($faqs),
            'categories' => $categories,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    /**
     * Display the specified published FAQ.
     */
    public function show(string $slug): Response
    {
        $faq = Faq::published()->where('slug', $slug)->firstOrFail();

        $this->faqService->incrementViewCount($faq->ulid);

        return Inertia::render('Public/Faq/Show', [
            'faq' => new FaqResource($faq),
        ]);
    }
}

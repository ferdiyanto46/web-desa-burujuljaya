<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService
    ) {}

    /**
     * Display a listing of the articles.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Article::class);

        $query = Article::withDetails()->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $articles = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Articles/Index', [
            'articles' => ArticleResource::collection($articles),
        ]);
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): Response
    {
        Gate::authorize('create', Article::class);

        $categories = ArticleCategory::all();

        return Inertia::render('Admin/Articles/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        /** @var UploadedFile|null $featuredImage */
        $featuredImage = $request->file('featured_image');

        $this->articleService->store($data, $featuredImage);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): Response
    {
        Gate::authorize('view', $article);

        $article->load(['category', 'author']);

        return Inertia::render('Admin/Articles/Show', [
            'article' => new ArticleResource($article),
        ]);
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): Response
    {
        Gate::authorize('update', $article);

        $categories = ArticleCategory::all();

        return Inertia::render('Admin/Articles/Edit', [
            'article' => new ArticleResource($article),
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified article in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $featuredImage */
        $featuredImage = $request->file('featured_image');

        $this->articleService->update($article, $data, $featuredImage);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        Gate::authorize('delete', $article);

        $this->articleService->delete($article);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }
}

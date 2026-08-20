<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ArticleController extends Controller
{
    /**
     * Display a listing of published articles.
     */
    public function index(Request $request): Response
    {
        $query = Article::withDetails()->published()->latestPublished();

        $category = $request->string('kategori')->trim()->toString();
        if ($category !== '' && $category !== 'all' && $category !== 'semua') {
            $query->ofCategory($category);
        }

        $search = $request->string('cari')->trim()->toString();
        if ($search !== '') {
            $query->search($search);
        }

        $articles = $query->paginate(9)->withQueryString();
        $categories = ArticleCategory::all();

        return Inertia::render('Public/Articles/Index', [
            'articles' => $articles,
            'categories' => $categories,
            'filters' => [
                'kategori' => $category,
                'cari' => $search,
            ],
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(string $slug): Response
    {
        $article = Article::withDetails()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views in background ideally, but we do it synchronously for now
        $article->increment('views');

        $relatedArticles = Article::withDetails()
            ->published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latestPublished()
            ->take(3)
            ->get();

        return Inertia::render('Public/Articles/Show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}

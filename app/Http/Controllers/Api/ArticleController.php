<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
            app()->setLocale($locale);
            
        $validated = $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $perPage = $validated['per_page'] ?? 10;

        $articles = Article::query()
            ->published()
            ->latest('published_at')
            ->paginate($perPage);

        return ArticleResource::collection($articles);
    }

    public function show(string $slug, Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
        app()->setLocale($locale);
        
        $article = Article::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedQuery = Article::query()
            ->published()
            ->whereKeyNot($article->id);

        $related = $relatedQuery
            ->latest('published_at')
            ->limit(5)
            ->get();

        return (new ArticleResource($article))
            ->additional([
                'related' => ArticleResource::collection($related),
            ]);
    }
}
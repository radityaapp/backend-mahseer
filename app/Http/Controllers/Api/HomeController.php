<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ArticleResource;
use App\Http\Resources\Api\TestimonialResource;
use App\Http\Resources\Api\ActivityResource;
use App\Models\Product;
use App\Models\Article;
use App\Models\Testimonial;
use App\Models\Activity;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
        app()->setLocale($locale);

        $productsLimit     = (int) $request->query('products_limit', 4);
        $articlesLimit     = (int) $request->query('articles_limit', 3);
        $testimonialsLimit = (int) $request->query('testimonials_limit', 5);
        $activitiesLimit   = (int) $request->query('activities_limit', 10);

        $products = Product::query()
            ->with(['category', 'media'])
            ->active()
            ->orderByDesc('id')
            ->limit($productsLimit)
            ->get();

        $articles = Article::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->limit($articlesLimit)
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->limit($testimonialsLimit)
            ->get();

        $activities = Activity::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->limit($activitiesLimit)
            ->get();

        return response()->json([
            'locale' => $locale,

            'products' => ProductResource::collection($products)
                ->toArray($request),

            'articles' => ArticleResource::collection($articles)
                ->toArray($request),

            'testimonials' => TestimonialResource::collection($testimonials)
                ->toArray($request),

            'activities' => ActivityResource::collection($activities)
                ->toArray($request),
        ]);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
            app()->setLocale($locale);

        $validated = $request->validate([
            'category'  => ['nullable', 'string'],
            'sort'      => ['nullable', 'in:termurah,termahal'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:100'],
            'currency'   => ['nullable', 'string', 'size:3'],
        ]);

            $perPage  = $validated['per_page'] ?? 12;
            $category = $validated['category'] ?? null;
            $sort     = $validated['sort'] ?? null;

        $query = Product::query()
            ->with(['category'])
            ->active()
            ->categorySlug($category)
            ->when(isset($validated['min_price']), function ($q) use ($validated) {
                $q->where('price', '>=', $validated['min_price']);
            })
            ->when(isset($validated['max_price']), function ($q) use ($validated) {
                $q->where('price', '<=', $validated['max_price']);
            })
            ->sortByPrice($sort);

        $products = $query->paginate($perPage);

        return ProductResource::collection($products)
            ->additional([
                'filters' => [
                    'lang'      => $locale,
                    'category'  => $category,
                    'sort'      => $sort,
                    'min_price' => $validated['min_price'] ?? null,
                    'max_price' => $validated['max_price'] ?? null,
                    'currency'  => $validated['currency'] ?? null,
                ],
            ]);
    }

    public function show(string $slug, Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
        app()->setLocale($locale);

        $product = Product::query()
            ->with(['category', 'media'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedQuery = Product::query()
            ->with(['category', 'media'])
            ->active()
            ->whereKeyNot($product->id);

        if ($product->category_id) {
            $relatedQuery->where('category_id', $product->category_id);
    }

        $related = $relatedQuery
            ->latest('id')
            ->limit(8)
            ->get();

        return (new ProductResource($product))
            ->additional([
                'related' => ProductResource::collection($related),
            ]);
    }
}
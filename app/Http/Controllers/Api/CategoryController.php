<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
        app()->setLocale($locale);
        
        $type = $request->query('type', 'product');

        $categories = Category::query()
            ->where('type', $type)
            ->orderBy('id')
            ->withCount('products')
            ->get();

        return $categories->map(function (Category $category) use ($locale) {
            return [
                'id'            => $category->id,
                'slug'          => $category->slug,
                'product_count' => $category->products_count,
                'name'          => $category->getTranslation('name', $locale),
            ];
        });
    }

    public function show(Category $category, Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
        app()->setLocale($locale);

        $category->loadCount('products');

        return [
            'id'            => $category->id,
            'slug'          => $category->slug,
            'product_count' => $category->products_count,
            'name'          => $category->getTranslation('name', $locale),
        ];
    }
}
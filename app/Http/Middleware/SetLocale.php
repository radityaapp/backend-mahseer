<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.locale');

        if ($request->has('lang')) {
            $locale = $request->get('lang');
            session(['app_locale' => $locale]);
        }

        if (session()->has('app_locale')) {
            $locale = session('app_locale');
        }

        $allowedLocales = ['id', 'en'];

        if (! in_array($locale, $allowedLocales)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
            app()->setLocale($locale);
            
        $testimonials = Testimonial::query()
            ->active()
            ->ordered()
            ->get();

        return TestimonialResource::collection($testimonials);
    }
}
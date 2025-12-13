<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->query('lang', config('app.locale'));
            app()->setLocale($locale);
        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $limit = $validated['limit'] ?? 10;

        $activities = Activity::query()
            ->active()
            ->ordered()
            ->limit($limit)
            ->get();

        return ActivityResource::collection($activities);
    }
}
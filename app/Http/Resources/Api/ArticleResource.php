<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        
        $heroImage = $this->getFirstMediaUrl('article_images');
        $images = $this->getMedia('article_images')
            ->map(fn ($media) => $media->getUrl())
            ->toArray();
        $authorName = $this->author
            ?? optional($this->creator)->name
            ?? null;

        $frontendBase = config('app.frontend_url');
        $frontendUrl  = $frontendBase
            ? rtrim($frontendBase, '/') . '/articles/' . $this->slug
            : null;

        return [
            'id'            => $this->id,
            'slug'          => $this->slug,
            'title'         => $this->getTranslation('title', $locale),
            'excerpt'       => $this->getTranslation('excerpt', $locale),
            'content'       => $this->getTranslation('content', $locale),

            'author'        => $authorName,
            'published_at'  => optional($this->published_at)->toIso8601String(),
            'published_at_human' => optional($this->published_at)
                ? $this->published_at->translatedFormat('d F Y')
                : null,

            'hero_image'    => $heroImage,
            'images'        => $images,
            'frontend_url'  => $frontendUrl,
        ];
    }
}
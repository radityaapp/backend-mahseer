<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $table = 'article';
    protected $appends = ['featured_image_url', 'content_images_urls'];
    protected $hidden = ['media'];

    protected $fillable = [
        'created_by',
        'author',
        'title',
        'content',
        'excerpt',
        'slug',
        'is_published',
        'published_at',
    ];

    public $translatable = [
        'title', 
        'content',
        'excerpt',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
    
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
    
    public function registerMediaCollections(): void
        {
        $this->addMediaCollection('featured_image')
        ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
        ->singleFile();

        $this->addMediaCollection('content_images')
        ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg']);
        }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('featured_image') ?: null;
    }

    public function getContentImagesUrlsAttribute(): array
    {
        return $this->getMedia('content_images')
            ->map(fn ($media) => $media->getUrl())
            ->toArray();
    }
}
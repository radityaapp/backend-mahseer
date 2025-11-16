<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $table = 'article';
    protected $appends = ['image_urls'];
    protected $hidden = ['media'];

    protected $fillable = [
        'created_by',
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
    
    public function registerMediaCollections(): void
        {
            $this->addMediaCollection('article_images')
                ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg']);
        }

    public function getImageUrlsAttribute()
    {
        return $this->getMedia('article_images')->map(function ($media) {
            return $media->getUrl();
        })->toArray();
    }
}
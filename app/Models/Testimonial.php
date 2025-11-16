<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model implements HasMedia
{
use HasFactory, InteractsWithMedia, HasTranslations;

    protected $table = 'testimonial';
    protected $appends = ['image_urls'];
    protected $hidden = ['media'];

    protected $fillable = [
        'name',
        'description',
        'institution',
        'is_active',
        'order',
    ];

    public $translatable = [
        'description',
        'institution',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->singleFile();
    }

    public function getImageUrlsAttribute()
    {
        return $this->getMedia('avatar')->map(function ($media) {
            return $media->getUrl();
        })->toArray();
    }
}
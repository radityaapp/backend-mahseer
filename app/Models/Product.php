<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use App\Services\CurrencyConverter;
use App\Models\Currency;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $table = 'product';
    protected $appends = ['image_urls', 'prices_in_currencies'];
    protected $hidden = ['media'];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'general_information',
        'description',
        'price',
        'stock',
        'is_active',
    ];
    
    public $translatable = [
        'name', 
        'general_information',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function registerMediaCollections(): void
        {
            $this->addMediaCollection('product_images')
                ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg']);
        }
        
        public function getImageUrlsAttribute()
    {
        return $this->getMedia('product_images')->map(function ($media) {
            return $media->getUrl();
        })->toArray();
    }

    public function getPricesInCurrenciesAttribute(): array
    {
        $currencies = Currency::where('is_active', true)->get();
        $prices = [];

        $amount = (float) $this->price;

        foreach ($currencies as $currency) {
            $convertedPrice = app('App\Services\CurrencyConverter')->convert(
                $amount,
                Currency::base()->code,
                $currency->code
            );

            $prices[$currency->code] = round($convertedPrice, 2);
        }

        return $prices;
    }
}
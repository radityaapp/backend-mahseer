<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use App\Models\Currency;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Builder;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $table = 'product';
    protected $appends = ['image_urls', 'prices_in_currencies', 'whatsapp_url', 'tokopedia_buy_url'];
    protected $hidden = ['media'];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'general_information',
        'description',
        'price',
        'tokopedia_url',
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
    
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeCategorySlug(Builder $query, ?string $slug): Builder
    {
        if (! $slug || $slug === 'all') {
        return $query;
    }

        return $query->whereHas('category', function (Builder $q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    public function scopeSortByPrice(Builder $query, ?string $direction): Builder
    {
        return match ($direction) {
            'termurah'  => $query->orderBy('price', 'asc'),
            'termahal' => $query->orderBy('price', 'desc'),
            default     => $query->latest('id'),
        };
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        $number = config('mahseer.whatsapp_number');

        if (! $number) {
            return null;
    }

        $locale = app()->getLocale();
        $name = $this->getTranslation('name', $locale) ?? $this->name;

        $price = $this->price ?? $this->price_base ?? null;

        $priceText = $price
            ? number_format((float) $price, 0, ',', '.')
            : null;

        $frontendUrl = config('app.frontend_url', 'https://example.com') . '/products/' . $this->slug;

        $message = "Halo, saya tertarik dengan produk {$name}";

        if ($priceText) {
            $message .= " (harga sekitar Rp{$priceText})";
        }

        $message .= ". Link produk: {$frontendUrl}. Apakah masih tersedia?";

        return 'https://wa.me/' . $number . '?text=' . urlencode($message);
    }

    public function getTokopediaBuyUrlAttribute(): ?string
    {
        if (! empty($this->tokopedia_url)) {
            return $this->tokopedia_url;
        }

        $shopUrl = config('mahseer.tokopedia_shop_url');

        return $shopUrl ?: null;
    }
}
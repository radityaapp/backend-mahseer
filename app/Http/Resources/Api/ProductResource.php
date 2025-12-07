<?php

namespace App\Http\Resources\Api;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale        = app()->getLocale();
        $prices        = $this->prices_in_currencies ?? [];
        $baseCurrency  = Currency::base()?->code ?? 'IDR';

        $displayCurrency = strtoupper(
            $request->query('currency', $baseCurrency)
        );

        $displayPrice = $prices[$displayCurrency] ?? (float) $this->price;


        return [
            'id'                    => $this->id,
            'slug'                  => $this->slug,
            'name'                  => $this->getTranslation('name', $locale),
            'general_information'   => $this->getTranslation('general_information', $locale),
            'description'           => $this->getTranslation('description', $locale),
            'price_base'            => (float) $this->price,
            'prices'                => $prices,
            'display_currency'      => $displayCurrency,
            'display_price'         => (float) $displayPrice,
            'stock'                 => $this->stock,
            'is_active'             => (bool) $this->is_active,
            'buy_links'             => [
                'whatsapp'          => $this->whatsapp_url,
                'tokopedia'         => $this->tokopedia_buy_url,
            ],

            'category' => $this->whenLoaded('category', function () use ($locale) {
                return [
                    'id'    => $this->category->id,
                    'slug'  => $this->category->slug,
                    'name'  => $this->category->getTranslation('name', $locale),
                ];
            }),

            'images'        => $this->image_urls,
            'image_cover'   => $this->getFirstMediaUrl('product_images') ?: null,
        ];
    }
}
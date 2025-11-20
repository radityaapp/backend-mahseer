<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id'            => $this->id,
            'slug'          => $this->slug,
            'name'          => $this->getTranslation('name', $locale),
            'type'          => $this->type,
            'product_count' => $this->whenCounted('products'),
        ];
    }
}
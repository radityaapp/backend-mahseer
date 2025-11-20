<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'institution' => $this->getTranslation('institution', $locale),
            'description' => $this->getTranslation('description', $locale),
            'order'       => $this->order,
            'is_active'   => (bool) $this->is_active,
            'avatar'      => $this->image_urls[0] ?? null,
            'images'      => $this->image_urls,
        ];
    }
}
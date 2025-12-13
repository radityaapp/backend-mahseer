<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();
        $heroImage = $this->getFirstMediaUrl('featured_image') ?: null;

        return [
            'id'          => $this->id,
            'title'       => $this->getTranslation('title', $locale),
            'description' => $this->getTranslation('description', $locale),
            'external_url'=> $this->external_url,
            'order'       => $this->order,
            'image'       => $heroImage,
            'is_active'   => $this->is_active,
        ];
    }
}
<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id'          => $this->id,
            'external_url'=> $this->external_url,
            'order'       => $this->order,
            'image'       => $this->getFirstMediaUrl('activity_image') ?: null,
            'is_active'   => $this->is_active,
        ];
    }
}
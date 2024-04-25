<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'media_path' => $this->media_path,
            'media_url' => $this->media_url,
            'media_type' => $this->media_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

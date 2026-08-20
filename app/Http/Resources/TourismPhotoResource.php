<?php

namespace App\Http\Resources;

use App\Models\TourismPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TourismPhoto
 */
class TourismPhotoResource extends JsonResource
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
            'ulid' => $this->ulid,
            'file_url' => $this->file_url,
            'caption' => $this->caption,
            'order' => $this->order,
        ];
    }
}

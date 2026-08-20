<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductPhoto
 */
class ProductPhotoResource extends JsonResource
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
            'product_id' => $this->product_id,
            'image_path' => $this->image_path,
            'caption' => $this->caption,
            'alt_text' => $this->alt_text,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

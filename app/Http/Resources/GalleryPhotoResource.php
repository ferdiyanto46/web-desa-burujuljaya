<?php

namespace App\Http\Resources;

use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin GalleryPhoto
 */
class GalleryPhotoResource extends JsonResource
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
            'gallery_id' => $this->gallery_id,
            'image_path' => $this->image_path,
            'image_url' => asset('storage/'.$this->image_path),
            'caption' => $this->caption,
            'alt_text' => $this->alt_text,
            'photographer' => $this->photographer,
            'file_size' => $this->file_size,
            'width' => $this->width,
            'height' => $this->height,
            'mime' => $this->mime,
            'extension' => $this->extension,
            'disk' => $this->disk,
            'sort_order' => $this->sort_order,

            'relationships' => [
                'gallery' => new GalleryResource($this->whenLoaded('gallery')),
            ],

            'permissions' => [
                'update' => $request->user()?->can('gallery-photos.update') ?? false,
                'delete' => $request->user()?->can('gallery-photos.delete') ?? false,
            ],

            'timestamps' => [
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ],
        ];
    }
}

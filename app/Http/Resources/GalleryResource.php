<?php

namespace App\Http\Resources;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Gallery
 */
class GalleryResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'featured_image_path' => $this->featured_image_path,
            'featured_image_url' => $this->featured_image_path ? asset('storage/'.$this->featured_image_path) : null,
            'status' => $this->status?->value,
            'published_at' => $this->published_at?->toISOString(),
            'is_pinned' => $this->is_pinned,

            'relationships' => [
                'author' => $this->whenLoaded('author', fn () => [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                    'email' => $this->author->email,
                ]),
                'photos' => GalleryPhotoResource::collection($this->whenLoaded('photos')),
            ],

            'permissions' => [
                'update' => $request->user()?->can('galleries.update') ?? false,
                'delete' => $request->user()?->can('galleries.delete') ?? false,
            ],

            'timestamps' => [
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ],

            'photos_count' => $this->whenCounted('photos'),
        ];
    }
}

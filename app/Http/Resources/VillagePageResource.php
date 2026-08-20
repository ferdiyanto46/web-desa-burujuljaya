<?php

namespace App\Http\Resources;

use App\Models\VillagePage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin VillagePage
 */
class VillagePageResource extends JsonResource
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
            'page_type' => $this->page_type->value,
            'page_type_label' => $this->page_type->label(),
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image_url' => $this->featured_image_path ? Storage::url($this->featured_image_path) : null,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'is_pinned' => $this->is_pinned,
            'sort_order' => $this->sort_order,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'canonical_url' => $this->canonical_url,
            'author' => $this->whenLoaded('author', fn () => ['id' => $this->author->id, 'name' => $this->author->name]),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}

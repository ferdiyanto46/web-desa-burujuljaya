<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Umkm
 */
class UmkmResource extends JsonResource
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
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'owner_name' => $this->owner_name,
            'description' => $this->description,
            'featured_image_path' => $this->featured_image_path,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'google_maps_url' => $this->google_maps_url,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'website' => $this->website,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'operating_hours' => $this->operating_hours,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'is_featured' => $this->is_featured,
            'is_pinned' => $this->is_pinned,
            'view_count' => $this->view_count,
            'featured_order' => $this->featured_order,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'category' => new UmkmCategoryResource($this->whenLoaded('category')),
            'user' => $this->whenLoaded('user'),
            'photos' => $this->whenLoaded('photos'),
        ];
    }
}

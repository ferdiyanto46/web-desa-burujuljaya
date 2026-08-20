<?php

namespace App\Http\Resources;

use App\Models\Tourism;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Tourism
 */
class TourismResource extends JsonResource
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
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'featured_image_url' => $this->featured_image_url,

            // Location
            'address' => $this->address,
            'coordinates' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'google_maps_url' => $this->google_maps_url,

            // Info
            'opening_hours' => $this->opening_hours,
            'ticket_price' => $this->ticket_price,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'website' => $this->website,

            // Flags
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'featured_order' => $this->featured_order,
            'is_pinned' => $this->is_pinned,
            'view_count' => $this->view_count,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

            // SEO
            'seo' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'og_image_url' => $this->og_image ? Storage::disk('public')->url($this->og_image) : null,
                'canonical_url' => $this->canonical_url,
            ],

            // Relations
            'category' => new TourismCategoryResource($this->whenLoaded('category')),
            'author' => [
                'id' => $this->whenLoaded('author', fn () => $this->author->id),
                'name' => $this->whenLoaded('author', fn () => $this->author->name),
            ],
            'facilities' => TourismFacilityResource::collection($this->whenLoaded('facilities')),
            'gallery' => TourismPhotoResource::collection($this->whenLoaded('photos')),
            'photo_count' => $this->whenLoaded('photos', fn () => $this->photos->count(), 0),
        ];
    }
}

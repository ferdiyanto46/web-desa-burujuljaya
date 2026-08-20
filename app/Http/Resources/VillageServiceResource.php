<?php

namespace App\Http\Resources;

use App\Models\VillageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

/**
 * @mixin VillageService
 */
class VillageServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Identifiers
            'id' => $this->id,
            'ulid' => $this->ulid,

            // Domain Specific Fields
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'featured_image_path' => $this->featured_image_path,
            'featured_image_url' => $this->featured_image_path ? asset('storage/'.$this->featured_image_path) : null,

            'requirements' => $this->requirements,
            'procedures' => $this->procedures,
            'processing_time' => $this->processing_time,
            'cost' => $this->cost,
            'service_hours' => $this->service_hours,

            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'email' => $this->email,

            'downloadable_form_path' => $this->downloadable_form_path,
            'downloadable_form_name' => $this->downloadable_form_name,
            'downloadable_form_disk' => $this->downloadable_form_disk,
            'downloadable_form_size' => $this->downloadable_form_size,
            'downloadable_form_mime' => $this->downloadable_form_mime,
            'downloadable_form_extension' => $this->downloadable_form_extension,
            'downloadable_form_url' => $this->downloadable_form_path ? asset('storage/'.$this->downloadable_form_path) : null,

            'is_pinned' => $this->is_pinned,
            'sort_order' => $this->sort_order,

            // Metadata
            'meta' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'og_image_path' => $this->og_image, // Note: migration called it og_image
            ],

            // Status & Lifecycles
            'status' => $this->status,
            'published_at' => $this->published_at?->format('c'),

            // Relationships
            'user_id' => $this->user_id,
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'email' => $this->author->email,
            ]),

            // Timestamps
            'timestamps' => [
                'created_at' => $this->created_at?->format('c'),
                'updated_at' => $this->updated_at?->format('c'),
                'deleted_at' => $this->deleted_at?->format('c'),
            ],

            // Permissions
            'permissions' => [
                'view' => $request->user()?->can('view', $this->resource) ?? false,
                'update' => $request->user()?->can('update', $this->resource) ?? false,
                'delete' => $request->user()?->can('delete', $this->resource) ?? false,
            ],

            // Links
            'links' => [
                'self' => Route::has('admin.village-services.show') ? route('admin.village-services.show', $this->ulid) : null,
                'edit' => Route::has('admin.village-services.edit') ? route('admin.village-services.edit', $this->ulid) : null,
            ],
        ];
    }
}

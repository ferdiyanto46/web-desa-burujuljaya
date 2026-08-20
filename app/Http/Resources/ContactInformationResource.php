<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ContactInformation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

/**
 * @mixin ContactInformation
 */
class ContactInformationResource extends JsonResource
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

            'address' => $this->address,
            'google_maps_embed' => $this->google_maps_embed,
            'google_maps_url' => $this->google_maps_url,

            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'website' => $this->website,

            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'youtube' => $this->youtube,
            'tiktok' => $this->tiktok,

            'office_hours' => $this->office_hours,
            'emergency_contact' => $this->emergency_contact,

            'featured_image_path' => $this->featured_image_path,

            'is_pinned' => $this->is_pinned,
            'sort_order' => $this->sort_order,

            // Metadata
            'meta' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'canonical_url' => $this->canonical_url,
                'og_image' => $this->og_image,
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
                'restore' => $request->user()?->can('restore', $this->resource) ?? false,
                'force_delete' => $request->user()?->can('forceDelete', $this->resource) ?? false,
            ],

            // Links
            'links' => [
                'self' => Route::has('admin.contact-information.show') ? route('admin.contact-information.show', $this->ulid) : null,
                'edit' => Route::has('admin.contact-information.edit') ? route('admin.contact-information.edit', $this->ulid) : null,
            ],
        ];
    }
}

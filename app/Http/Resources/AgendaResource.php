<?php

namespace App\Http\Resources;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

/**
 * @mixin Agenda
 */
class AgendaResource extends JsonResource
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
            'content' => $this->content,
            'location' => $this->location,
            'location_url' => $this->location_url,
            'featured_image_path' => $this->featured_image_path,
            'start_datetime' => $this->start_datetime->format('c'),
            'end_datetime' => $this->end_datetime?->format('c'),

            // Status & Lifecycles
            'status' => $this->status,
            'is_cancelled' => $this->is_cancelled,
            'event_status' => $this->event_status,
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
                'self' => Route::has('admin.agendas.show') ? route('admin.agendas.show', $this->ulid) : null,
                'edit' => Route::has('admin.agendas.edit') ? route('admin.agendas.edit', $this->ulid) : null,
            ],
        ];
    }
}

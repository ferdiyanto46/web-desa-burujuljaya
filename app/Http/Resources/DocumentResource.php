<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

/**
 * @mixin Document
 */
class DocumentResource extends JsonResource
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
            'description' => $this->description,
            'is_pinned' => $this->is_pinned,

            // File Metadata
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'file_url' => asset('storage/'.$this->file_path),
            'mime' => $this->mime,
            'extension' => $this->extension,
            'file_size' => $this->file_size,
            'download_count' => $this->download_count,

            // Status
            'status' => $this->status,
            'published_at' => $this->published_at?->format('c'),

            // Relationships
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => optional($this->category)->id,
                    'name' => optional($this->category)->name,
                    'slug' => optional($this->category)->slug,
                ];
            }),
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
                'self' => Route::has('admin.documents.show') ? route('admin.documents.show', $this->ulid) : null,
                'edit' => Route::has('admin.documents.edit') ? route('admin.documents.edit', $this->ulid) : null,
                'download' => Route::has('documents.download') ? route('documents.download', $this->ulid) : null,
            ],
        ];
    }
}

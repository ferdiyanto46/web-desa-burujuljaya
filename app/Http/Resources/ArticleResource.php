<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

/**
 * @mixin Article
 */
class ArticleResource extends JsonResource
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
            'content' => $this->content,
            'featured_image_path' => $this->featured_image_path,
            'cover_image_url' => $this->cover_image_url,
            'featured_image_url' => $this->featured_image_url,
            'views' => $this->views,
            'is_featured' => $this->is_featured,

            // Metadata
            'meta' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'og_image_path' => $this->og_image_path,
            ],

            // Status & Lifecycles
            'status' => $this->status,
            'published_at' => $this->published_at?->format('c'),

            // Relationships
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
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
                'self' => Route::has('admin.articles.show') ? route('admin.articles.show', $this->ulid) : null,
                'edit' => Route::has('admin.articles.edit') ? route('admin.articles.edit', $this->ulid) : null,
            ],
        ];
    }
}

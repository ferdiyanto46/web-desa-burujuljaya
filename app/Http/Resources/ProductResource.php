<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
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
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'weight' => $this->weight,
            'featured_image_path' => $this->featured_image_path,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'is_featured' => $this->is_featured,
            'is_pinned' => $this->is_pinned,
            'view_count' => $this->view_count,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'canonical_url' => $this->canonical_url,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),

            'author' => $this->whenLoaded('author', function () {
                return [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                ];
            }),
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
            'umkm' => new UmkmResource($this->whenLoaded('umkm')),
            'photos' => ProductPhotoResource::collection($this->whenLoaded('photos')),

            'links' => [
                'admin_show' => Route::has('admin.products.show') ? route('admin.products.show', $this->ulid) : null,
                'admin_edit' => Route::has('admin.products.edit') ? route('admin.products.edit', $this->ulid) : null,
                'public_show' => Route::has('products.show') ? route('products.show', $this->slug) : null,
            ],

            'permissions' => [
                'update' => $request->user()?->can('update', $this->resource) ?? false,
                'delete' => $request->user()?->can('delete', $this->resource) ?? false,
                'restore' => $request->user()?->can('restore', $this->resource) ?? false,
                'force_delete' => $request->user()?->can('forceDelete', $this->resource) ?? false,
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Faq
 */
class FaqResource extends JsonResource
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
            'question' => $this->question,
            'slug' => $this->slug,
            'answer' => $this->answer,
            'category' => $this->category,
            'sort_order' => $this->sort_order,
            'is_featured' => $this->is_featured,
            'status' => $this->status->value,
            'published_at' => $this->published_at?->toIso8601String(),
            'view_count' => $this->view_count,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'canonical_url' => $this->canonical_url,
            'author' => [
                'name' => $this->whenLoaded('author', fn () => $this->author->name),
            ],
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
        ];
    }
}

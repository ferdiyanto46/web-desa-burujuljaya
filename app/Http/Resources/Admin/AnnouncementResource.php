<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Announcement
 */
final class AnnouncementResource extends JsonResource
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
            'content' => $this->content,
            'status' => $this->status->value,
            'is_pinned' => $this->is_pinned,
            'featured_image_url' => $this->featured_image_path ? Storage::disk('public')->url($this->featured_image_path) : null,
            'published_at' => $this->published_at?->format('c'),
            'created_at' => $this->created_at?->format('c'),
            'updated_at' => $this->updated_at?->format('c'),
            'deleted_at' => $this->deleted_at?->format('c'),
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ]),
            'permissions' => [
                'view' => Gate::allows('view', $this->resource),
                'update' => Gate::allows('update', $this->resource),
                'delete' => Gate::allows('delete', $this->resource),
                'restore' => Gate::allows('restore', $this->resource),
                'force_delete' => Gate::allows('forceDelete', $this->resource),
            ],
            'links' => [
                'edit' => route('admin.announcements.edit', $this->resource),
                'update' => route('admin.announcements.update', $this->resource),
                'destroy' => route('admin.announcements.destroy', $this->resource),
                'restore' => route('admin.announcements.restore', $this->resource),
                'force_destroy' => route('admin.announcements.force-destroy', $this->resource),
            ],
        ];
    }
}

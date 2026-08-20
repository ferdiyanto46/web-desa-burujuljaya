<?php

namespace App\Http\Resources;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Complaint
 */
class ComplaintResource extends JsonResource
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
            'tracking_code' => $this->ulid,
            'category_id' => $this->category_id,
            'reporter_name' => $this->reporter_name,
            'reporter_email' => $this->reporter_email,
            'reporter_phone' => $this->reporter_phone,
            'title' => $this->title,
            'body' => $this->body,
            'is_anonymous' => (bool) $this->is_anonymous,
            'status' => $this->status,
            'resolved_at' => null, // Not currently tracked in the main model, tracked via histories
            'category' => new ComplaintCategoryResource($this->whenLoaded('category')),
            'attachments' => ComplaintAttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

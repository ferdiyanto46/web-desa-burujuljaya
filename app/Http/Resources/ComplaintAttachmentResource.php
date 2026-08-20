<?php

namespace App\Http\Resources;

use App\Models\ComplaintAttachment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ComplaintAttachment
 */
class ComplaintAttachmentResource extends JsonResource
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
            'complaint_id' => $this->complaint_id,
            'file_path' => $this->file_path,
            'file_name' => $this->file_name,
            'original_filename' => $this->file_name,
            'file_url' => asset('storage/'.$this->file_path),
            'mime_type' => $this->file_type,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

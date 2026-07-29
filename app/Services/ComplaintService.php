<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Complaint;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;

final class ComplaintService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Submit a new complaint.
     *
     * @param  array<string, mixed>  $data
     * @param  array<UploadedFile>  $attachments
     */
    public function submit(array $data, array $attachments = []): Complaint
    {
        return $this->db->transaction(function () use ($data, $attachments) {
            $complaint = clone new Complaint;

            $complaint->fill($data);
            $complaint->save();

            // Create initial history record
            $complaint->histories()->create([
                'actor_type' => 'citizen',
                'user_id' => null,
                'status_from' => null,
                'status_to' => 'unread',
                'notes' => 'Pengaduan baru dibuat.',
            ]);

            foreach ($attachments as $file) {
                $path = $file->store('complaints', 'public');
                if (is_string($path)) {
                    $complaint->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            return $complaint;
        });
    }

    /**
     * Update complaint status and add history log.
     */
    public function updateStatus(Complaint $complaint, string $status, ?string $notes = null, ?int $handledByUserId = null): Complaint
    {
        return $this->db->transaction(function () use ($complaint, $status, $notes, $handledByUserId) {
            /** @var string $oldStatus */
            $oldStatus = $complaint->status;

            $complaint->update(['status' => $status]);

            $complaint->histories()->create([
                'actor_type' => 'admin',
                'user_id' => $handledByUserId,
                'status_from' => $oldStatus,
                'status_to' => $status,
                'notes' => $notes,
            ]);

            return $complaint;
        });
    }

    /**
     * Remove a complaint.
     */
    public function remove(Complaint $complaint): void
    {
        $complaint->delete();
    }
}

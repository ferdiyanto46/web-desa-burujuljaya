<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Complaint;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;

final class ComplaintService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Get paginated complaints for admin panel.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Complaint>
     */
    public function getAdminPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = Complaint::query()->withDetails();

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest('id')->paginate(15)->withQueryString();
    }

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

            if (! empty($data['is_anonymous'])) {
                $data['reporter_name'] = ! empty($data['reporter_name']) ? $data['reporter_name'] : 'Anonim';
                $data['reporter_phone'] = $data['reporter_phone'] ?? null;
                $data['reporter_email'] = $data['reporter_email'] ?? null;
            }

            if (! empty($data['location'])) {
                $data['body'] = "Lokasi Kejadian: {$data['location']}\n\n{$data['body']}";
                unset($data['location']);
            }

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

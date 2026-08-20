<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Agenda;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Mews\Purifier\Facades\Purifier;

final class AgendaService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Schedule a new agenda.
     *
     * @param  array<string, mixed>  $data
     */
    public function schedule(array $data, ?UploadedFile $coverImage = null): Agenda
    {
        return $this->db->transaction(function () use ($data, $coverImage) {
            $agenda = clone new Agenda;

            $data['content'] = Purifier::clean($data['content'] ?? '');

            if ($coverImage) {
                $data['featured_image_path'] = $this->mediaService->store($coverImage, 'agendas');
            }

            $agenda->fill($data);
            $agenda->save();

            return $agenda;
        });
    }

    /**
     * Update an existing agenda.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Agenda $agenda, array $data, ?UploadedFile $coverImage = null): Agenda
    {
        return $this->db->transaction(function () use ($agenda, $data, $coverImage) {
            if (isset($data['content']) && is_string($data['content'])) {
                $data['content'] = Purifier::clean($data['content']);
            }

            if ($coverImage) {
                $data['featured_image_path'] = $this->mediaService->replace(
                    $coverImage,
                    $agenda->featured_image_path,
                    'agendas'
                );
            }

            $agenda->fill($data);
            $agenda->save();

            return $agenda;
        });
    }

    /**
     * Cancel (soft delete) an agenda.
     */
    public function cancel(Agenda $agenda): void
    {
        $agenda->delete();
    }

    /**
     * Restore a soft-deleted agenda.
     */
    public function restore(Agenda $agenda): void
    {
        $agenda->restore();
    }

    /**
     * Permanently delete an agenda and its media.
     */
    public function forceDelete(Agenda $agenda): void
    {
        if ($agenda->featured_image_path) {
            $this->mediaService->delete($agenda->featured_image_path);
        }

        $agenda->forceDelete();
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tourism;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Mews\Purifier\Facades\Purifier;

final class TourismService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Create a new tourism potential.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?UploadedFile $cover = null): Tourism
    {
        return $this->db->transaction(function () use ($data, $cover) {
            $tourism = clone new Tourism;

            $data['description'] = Purifier::clean($data['description'] ?? '');

            if ($cover) {
                $path = $cover->store('tourism', 'public');
                if (is_string($path)) {
                    $data['cover_image'] = $path;
                }
            }

            $tourism->fill($data);
            $tourism->save();

            return $tourism;
        });
    }

    /**
     * Update an existing tourism potential.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Tourism $tourism, array $data, ?UploadedFile $cover = null): Tourism
    {
        return $this->db->transaction(function () use ($tourism, $data, $cover) {
            if (isset($data['description']) && is_string($data['description'])) {
                $data['description'] = Purifier::clean($data['description']);
            }

            if ($cover) {
                $path = $cover->store('tourism', 'public');
                if (is_string($path)) {
                    $data['cover_image'] = $path;
                }
            }

            $tourism->fill($data);
            $tourism->save();

            return $tourism;
        });
    }

    /**
     * Remove (soft delete) a tourism potential.
     */
    public function remove(Tourism $tourism): void
    {
        $tourism->delete();
    }
}

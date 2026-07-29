<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Umkm;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Mews\Purifier\Facades\Purifier;

final class UmkmService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Create a new UMKM potential.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?UploadedFile $cover = null): Umkm
    {
        return $this->db->transaction(function () use ($data, $cover) {
            $umkm = clone new Umkm;

            $data['description'] = Purifier::clean($data['description'] ?? '');

            if ($cover) {
                $path = $cover->store('umkm', 'public');
                if (is_string($path)) {
                    $data['cover_image'] = $path;
                }
            }

            $umkm->fill($data);
            $umkm->save();

            return $umkm;
        });
    }

    /**
     * Update an existing UMKM potential.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Umkm $umkm, array $data, ?UploadedFile $cover = null): Umkm
    {
        return $this->db->transaction(function () use ($umkm, $data, $cover) {
            if (isset($data['description']) && is_string($data['description'])) {
                $data['description'] = Purifier::clean($data['description']);
            }

            if ($cover) {
                $path = $cover->store('umkm', 'public');
                if (is_string($path)) {
                    $data['cover_image'] = $path;
                }
            }

            $umkm->fill($data);
            $umkm->save();

            return $umkm;
        });
    }

    /**
     * Remove (soft delete) an UMKM potential.
     */
    public function remove(Umkm $umkm): void
    {
        $umkm->delete();
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Apparatus;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class ApparatusService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Appoint a new village apparatus.
     *
     * @param  array<string, mixed>  $data
     */
    public function appoint(array $data, ?UploadedFile $photo = null): Apparatus
    {
        return $this->db->transaction(function () use ($data, $photo) {
            $apparatus = new Apparatus;

            unset($data['photo']);

            if ($photo instanceof UploadedFile && $photo->isValid()) {
                $path = $photo->store('apparatus', 'public');
                if (is_string($path)) {
                    $data['photo_path'] = $path;
                }
            }

            $apparatus->fill($data);
            $apparatus->save();

            return $apparatus;
        });
    }

    /**
     * Update an existing village apparatus.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Apparatus $apparatus, array $data, ?UploadedFile $photo = null): Apparatus
    {
        return $this->db->transaction(function () use ($apparatus, $data, $photo) {
            unset($data['photo']);

            if ($photo instanceof UploadedFile && $photo->isValid()) {
                $oldPhoto = $apparatus->photo_path;

                $path = $photo->store('apparatus', 'public');
                if (is_string($path)) {
                    $data['photo_path'] = $path;

                    if (is_string($oldPhoto) && $oldPhoto !== $path && Storage::disk('public')->exists($oldPhoto)) {
                        Storage::disk('public')->delete($oldPhoto);
                    }
                }
            }

            $apparatus->fill($data);
            $apparatus->save();

            return $apparatus;
        });
    }

    /**
     * Remove an apparatus (soft delete).
     */
    public function remove(Apparatus $apparatus): void
    {
        $this->db->transaction(function () use ($apparatus) {
            $apparatus->delete();
        });
    }
}

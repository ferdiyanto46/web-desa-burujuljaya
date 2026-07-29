<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Apparatus;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;

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
            $apparatus = clone new Apparatus;

            if ($photo) {
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
            if ($photo) {
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
     * Retire or deactivate an apparatus.
     */
    public function retire(Apparatus $apparatus): Apparatus
    {
        $apparatus->update(['is_active' => false]);

        return $apparatus;
    }

    /**
     * Remove an apparatus completely.
     */
    public function remove(Apparatus $apparatus): void
    {
        $apparatus->delete();
    }
}

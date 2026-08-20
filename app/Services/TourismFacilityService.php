<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TourismFacility;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\LengthAwarePaginator;

final class TourismFacilityService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Get paginated facilities.
     */
    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = TourismFacility::query();

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get all facilities.
     */
    public function getAll()
    {
        return TourismFacility::orderBy('name')->get();
    }

    /**
     * Store a new facility.
     */
    public function store(array $data): TourismFacility
    {
        return $this->db->transaction(function () use ($data) {
            return TourismFacility::create([
                'name' => $data['name'],
                'icon' => $data['icon'] ?? 'check',
            ]);
        });
    }

    /**
     * Update an existing facility.
     */
    public function update(TourismFacility $facility, array $data): TourismFacility
    {
        return $this->db->transaction(function () use ($facility, $data) {
            $facility->update([
                'name' => $data['name'],
                'icon' => $data['icon'] ?? 'check',
            ]);

            return $facility;
        });
    }

    /**
     * Delete a facility.
     */
    public function delete(TourismFacility $facility): void
    {
        $this->db->transaction(function () use ($facility) {
            $facility->delete();
        });
    }
}

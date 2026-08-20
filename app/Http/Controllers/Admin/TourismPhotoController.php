<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourism;
use App\Services\TourismService;
use Illuminate\Http\RedirectResponse;

final class TourismPhotoController extends Controller
{
    public function __construct(
        private readonly TourismService $service
    ) {}

    /**
     * Remove the specified photo from storage.
     */
    public function destroy(Tourism $tourism, string $photoUlid): RedirectResponse
    {
        $this->authorize('update', $tourism); // The user must be able to update the tourism to delete its photos

        $this->service->deletePhoto($tourism, $photoUlid);

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}

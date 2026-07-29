<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateVillageProfileRequest;
use App\Services\VillageProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class VillageProfileController extends Controller
{
    public function __construct(
        private readonly VillageProfileService $profileService
    ) {}

    /**
     * Show the form for editing the village profile.
     */
    public function edit(): Response
    {
        $profile = $this->profileService->getActive();

        return Inertia::render('Admin/VillageProfile/Edit', [
            'profile' => $profile,
            'logoUrl' => $profile?->logoUrl(),
        ]);
    }

    /**
     * Update the village profile in storage.
     */
    public function update(UpdateVillageProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var UploadedFile|null $logo */
        $logo = $request->file('logo');

        $this->profileService->update($data, $logo);

        return redirect()->back()->with('success', 'Profil desa berhasil diperbarui.');
    }
}

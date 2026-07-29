<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class SettingController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService
    ) {}

    /**
     * Display a listing of the settings.
     */
    public function index(): Response
    {
        $settings = $this->settingService->getAll()->groupBy('group');

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update the specified settings in storage.
     */
    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        /** @var array<string, mixed> $settingsData */
        $settingsData = $request->validated('settings', []);

        foreach ($settingsData as $key => $value) {
            $this->settingService->update($key, $value);
        }

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}

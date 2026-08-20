<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\Setting;
use App\Services\MediaService;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class SettingController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService,
        private readonly MediaService $mediaService
    ) {}

    /**
     * Display a listing of the settings.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Setting::class);

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
        Gate::authorize('update', Setting::class);

        /** @var array<string, mixed> $settingsData */
        $settingsData = $request->validated('settings', []);

        if ($request->hasFile('banner_files')) {
            $bannerFiles = $request->file('banner_files');
            if (is_array($bannerFiles)) {
                foreach ($bannerFiles as $bannerKey => $file) {
                    if ($file instanceof UploadedFile) {
                        $settingKey = str_starts_with($bannerKey, 'page_banners.') ? $bannerKey : "page_banners.{$bannerKey}";
                        $existingSetting = Setting::where('key', $settingKey)->first();
                        $currentConfig = [];
                        if ($existingSetting && $existingSetting->value) {
                            $decoded = is_array($existingSetting->value) ? $existingSetting->value : json_decode((string) $existingSetting->value, true);
                            if (is_array($decoded)) {
                                $currentConfig = $decoded;
                            }
                        }

                        // Also merge any new title/subtitle from settingsData if present
                        if (isset($settingsData[$settingKey]) && is_array($settingsData[$settingKey])) {
                            $currentConfig = array_merge($currentConfig, $settingsData[$settingKey]);
                        }

                        $oldPath = $currentConfig['image_path'] ?? null;
                        $path = $this->mediaService->replace($file, $oldPath, 'banners');
                        if ($path) {
                            $currentConfig['image_path'] = $path;
                            $currentConfig['image_url'] = '/storage/'.ltrim($path, '/');
                            $settingsData[$settingKey] = $currentConfig;
                        }
                    }
                }
            }
        }

        foreach ($settingsData as $key => $value) {
            $this->settingService->update($key, $value);
        }

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}

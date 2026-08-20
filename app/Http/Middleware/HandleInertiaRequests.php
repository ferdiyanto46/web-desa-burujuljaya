<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use App\Services\VillageProfileService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'village' => fn () => ($profile = app(VillageProfileService::class)->getActive()) ? [
                'name' => $profile->village_name,
                'code' => $profile->village_code,
                'district' => $profile->district,
                'regency' => $profile->regency,
                'province' => $profile->province,
                'postal_code' => $profile->postal_code,
                'address' => $profile->address,
                'phone' => $profile->phone,
                'email' => $profile->email,
                'website_url' => $profile->website_url,
                'logo_url' => $profile->logoUrl(),
            ] : null,
            'page_banners' => fn () => ($banners = app(SettingService::class)->getAll()->where('group', 'page_banners')) ? $banners->mapWithKeys(function ($item) {
                $key = str_replace('page_banners.', '', $item->key);
                $val = is_array($item->value) ? $item->value : json_decode((string) $item->value, true);

                return [$key => $val];
            })->toArray() : [],
            'auth' => [
                'user' => $request->user(),
                'roles' => $request->user()?->getRoleNames() ?? [],
                'permissions' => $request->user()?->getAllPermissions()->pluck('name') ?? [],
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }
}

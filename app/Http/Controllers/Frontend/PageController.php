<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Apparatus;
use App\Models\VillageProfile;
use Inertia\Inertia;
use Inertia\Response;

final class PageController extends Controller
{
    /**
     * Display the village profile page.
     */
    public function profile(): Response
    {
        $profile = VillageProfile::firstOrFail();

        return Inertia::render('Public/Pages/Profile', [
            'profile' => $profile,
        ]);
    }

    /**
     * Display the village vision and mission page.
     */
    public function visionMission(): Response
    {
        $profile = VillageProfile::with('missions')->firstOrFail();

        return Inertia::render('Public/Pages/VisionMission', [
            'profile' => $profile,
            'missions' => $profile->missions,
        ]);
    }

    /**
     * Display the village apparatus page.
     */
    public function apparatus(): Response
    {
        $apparatuses = Apparatus::withDetails()->orderBy('position_id')->get();

        return Inertia::render('Public/Pages/Apparatus', [
            'apparatuses' => $apparatuses,
        ]);
    }
}

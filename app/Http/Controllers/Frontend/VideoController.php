<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Inertia\Inertia;
use Inertia\Response;

final class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index(): Response
    {
        $videos = Video::active()
            ->latest()
            ->paginate(12);

        return Inertia::render('Public/Videos/Index', [
            'videos' => $videos,
        ]);
    }
}

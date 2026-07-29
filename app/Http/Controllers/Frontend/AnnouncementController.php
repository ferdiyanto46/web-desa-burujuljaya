<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Inertia\Inertia;
use Inertia\Response;

final class AnnouncementController extends Controller
{
    /**
     * Display a listing of active announcements.
     */
    public function index(): Response
    {
        $announcements = Announcement::withDetails()
            ->active()
            ->orderBy('is_pinned', 'desc')
            ->latest('published_at')
            ->paginate(10);

        return Inertia::render('Public/Announcements/Index', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Display the specified announcement.
     */
    public function show(string $slug): Response
    {
        $announcement = Announcement::withDetails()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Public/Announcements/Show', [
            'announcement' => $announcement,
        ]);
    }
}

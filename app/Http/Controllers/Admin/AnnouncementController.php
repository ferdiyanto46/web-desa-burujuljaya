<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnnouncementRequest;
use App\Http\Requests\Admin\UpdateAnnouncementRequest;
use App\Http\Resources\Admin\AnnouncementResource;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class AnnouncementController extends Controller
{
    public function __construct(
        private readonly AnnouncementService $announcementService
    ) {}

    /**
     * Display a listing of the announcements.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Announcement::class);

        $query = Announcement::withDetails()->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('is_pinned')) {
            $query->where('is_pinned', $request->boolean('is_pinned'));
        }

        $announcements = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => AnnouncementResource::collection($announcements),
            'filters' => $request->only(['search', 'status', 'is_pinned']),
        ]);
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(): Response
    {
        Gate::authorize('create', Announcement::class);

        return Inertia::render('Admin/Announcements/Create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        Gate::authorize('create', Announcement::class);

        $data = $request->validated();

        $data['user_id'] = $request->user()->id;

        $this->announcementService->broadcast($data, $request->file('featured_image'));

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement): Response
    {
        Gate::authorize('update', $announcement);

        return Inertia::render('Admin/Announcements/Edit', [
            'announcement' => new AnnouncementResource($announcement),
        ]);
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        Gate::authorize('update', $announcement);

        $data = $request->validated();

        $this->announcementService->update($announcement, $data, $request->file('featured_image'));

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        Gate::authorize('delete', $announcement);

        $this->announcementService->revoke($announcement);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}

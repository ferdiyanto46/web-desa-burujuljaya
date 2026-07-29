<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVideoRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class VideoController extends Controller
{
    public function __construct(
        private readonly VideoService $videoService
    ) {}

    /**
     * Display a listing of the videos.
     */
    public function index(): Response
    {
        $videos = Video::latest()->paginate(15);

        return Inertia::render('Admin/Videos/Index', [
            'videos' => $videos,
        ]);
    }

    /**
     * Show the form for creating a new video.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Videos/Create');
    }

    /**
     * Store a newly created video in storage.
     */
    public function store(StoreVideoRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->videoService->embed($data);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit(Video $video): Response
    {
        return Inertia::render('Admin/Videos/Edit', [
            'video' => $video,
        ]);
    }

    /**
     * Update the specified video in storage.
     */
    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse
    {
        $data = $request->validated();

        $this->videoService->update($video, $data);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil diperbarui.');
    }

    /**
     * Remove the specified video from storage.
     */
    public function destroy(Video $video): RedirectResponse
    {
        $this->videoService->remove($video);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil dihapus.');
    }
}

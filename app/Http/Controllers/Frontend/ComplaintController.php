<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SubmitComplaintRequest;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Services\ComplaintService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

final class ComplaintController extends Controller
{
    public function __construct(
        private readonly ComplaintService $complaintService
    ) {}

    /**
     * Show the form for creating a new complaint.
     */
    public function create(): Response
    {
        $categories = ComplaintCategory::all();

        return Inertia::render('Public/Complaints/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created complaint in storage.
     */
    public function store(SubmitComplaintRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var array<UploadedFile> $attachments */
        $attachments = $request->file('attachments') ?? [];

        $complaint = $this->complaintService->submit($data, $attachments);

        return redirect()->route('complaints.track', ['tiket' => $complaint->ulid])
            ->with('success', 'Pengaduan Anda berhasil dikirim. Harap simpan Nomor Tiket Anda untuk pelacakan.');
    }

    /**
     * Track a complaint status.
     */
    public function track(Request $request): Response
    {
        $tiket = $request->string('tiket')->toString();
        $complaint = null;

        if ($tiket) {
            $complaint = Complaint::withDetails()
                ->where('ulid', $tiket)
                ->first();
        }

        return Inertia::render('Public/Complaints/Track', [
            'tiket' => $tiket,
            'complaint' => $complaint,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateComplaintRequest;
use App\Models\Complaint;
use App\Services\ComplaintService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ComplaintController extends Controller
{
    public function __construct(
        private readonly ComplaintService $complaintService
    ) {}

    /**
     * Display a listing of the complaints.
     */
    public function index(): Response
    {
        $complaints = Complaint::withDetails()->latest()->paginate(15);

        return Inertia::render('Admin/Complaints/Index', [
            'complaints' => $complaints,
        ]);
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint): Response
    {
        $complaint->load(['category', 'attachments', 'histories.user']);

        return Inertia::render('Admin/Complaints/Show', [
            'complaint' => $complaint,
        ]);
    }

    /**
     * Update the specified complaint status.
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validated();

        $this->complaintService->updateStatus(
            $complaint,
            $data['status'],
            $data['notes'] ?? null,
            auth()->id()
        );

        return redirect()->route('admin.complaints.show', $complaint)->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /**
     * Remove the specified complaint from storage.
     */
    public function destroy(Complaint $complaint): RedirectResponse
    {
        $this->complaintService->remove($complaint);

        return redirect()->route('admin.complaints.index')->with('success', 'Pengaduan berhasil dihapus.');
    }
}

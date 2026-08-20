<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Services\ComplaintService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'category_id', 'status']);
        $complaints = $this->complaintService->getAdminPaginated($filters);
        $categories = ComplaintCategory::select('id', 'name')->get();

        return Inertia::render('Admin/Complaints/Index', [
            'complaints' => ComplaintResource::collection($complaints),
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint): Response
    {
        $complaint->load(['category', 'attachments', 'histories.user']);

        return Inertia::render('Admin/Complaints/Show', [
            'complaint' => ComplaintResource::make($complaint),
        ]);
    }

    /**
     * Update the specified complaint status.
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validated();

        $userId = Auth::id();
        $this->complaintService->updateStatus(
            $complaint,
            $data['status'],
            $data['notes'] ?? null,
            $userId !== null ? (int) $userId : null
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

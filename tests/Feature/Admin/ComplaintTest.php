<?php

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Role::firstOrCreate(['name' => 'super-admin']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('super-admin');

    $this->category = ComplaintCategory::factory()->create();
});

it('renders complaint index page for admin', function () {
    Complaint::factory()->count(3)->create(['category_id' => $this->category->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.complaints.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Complaints/Index')
            ->has('complaints.data', 3)
        );
});

it('renders complaint show page for admin', function () {
    $complaint = Complaint::factory()->create([
        'category_id' => $this->category->id,
        'title' => 'Laporan Masalah Jembatan',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.complaints.show', $complaint->id))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Complaints/Show')
            ->where('complaint.data.title', 'Laporan Masalah Jembatan')
        );
});

it('allows admin to update complaint status', function () {
    $complaint = Complaint::factory()->create([
        'category_id' => $this->category->id,
        'status' => 'unread',
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('admin.complaints.update', $complaint->id), [
            'status' => 'processing',
            'notes' => 'Petugas telah dikirim ke lokasi.',
        ]);

    $response->assertRedirect(route('admin.complaints.show', $complaint));

    $complaint->refresh();
    expect($complaint->status)->toBe('processing');

    $this->assertDatabaseHas('complaint_histories', [
        'complaint_id' => $complaint->id,
        'status_to' => 'processing',
        'notes' => 'Petugas telah dikirim ke lokasi.',
    ]);
});

it('allows admin to delete a complaint', function () {
    $complaint = Complaint::factory()->create(['category_id' => $this->category->id]);

    $response = $this->actingAs($this->admin)
        ->delete(route('admin.complaints.destroy', $complaint->id));

    $response->assertRedirect(route('admin.complaints.index'));

    $this->assertSoftDeleted('complaints', [
        'id' => $complaint->id,
    ]);
});

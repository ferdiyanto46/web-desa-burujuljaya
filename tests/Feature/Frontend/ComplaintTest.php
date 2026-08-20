<?php

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('renders public complaint create page', function () {
    ComplaintCategory::factory()->create(['name' => 'Infrastruktur']);

    $this->get(route('complaints.create'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Public/Complaints/Create')
            ->has('categories.data', 1)
        );
});

it('allows a citizen to submit a named complaint', function () {
    Storage::fake('public');
    $category = ComplaintCategory::factory()->create();

    $payload = [
        'category_id' => $category->id,
        'reporter_name' => 'Budi Santoso',
        'reporter_phone' => '08123456789',
        'reporter_email' => 'budi@example.com',
        'title' => 'Lampu Jalan Mati',
        'body' => 'Lampu penerangan jalan di RW 02 mati total.',
        'location' => 'Jalan Melati RW 02',
        'is_anonymous' => false,
    ];

    $response = $this->post(route('complaints.store'), $payload);

    $complaint = Complaint::latest('id')->first();
    expect($complaint)->not->toBeNull();
    expect($complaint->title)->toBe('Lampu Jalan Mati');
    expect($complaint->reporter_name)->toBe('Budi Santoso');
    expect($complaint->is_anonymous)->toBeFalse();

    $response->assertRedirect(route('complaints.track', ['tiket' => $complaint->ulid]));
});

it('allows a citizen to submit an anonymous complaint', function () {
    $category = ComplaintCategory::factory()->create();

    $payload = [
        'category_id' => $category->id,
        'title' => 'Pengaduan Sampah Liar',
        'body' => 'Banyak sampah menumpuk di dekat sungai.',
        'location' => 'Dekat jembatan RT 04',
        'is_anonymous' => true,
    ];

    $response = $this->post(route('complaints.store'), $payload);

    $complaint = Complaint::latest('id')->first();
    expect($complaint)->not->toBeNull();
    expect($complaint->title)->toBe('Pengaduan Sampah Liar');
    expect($complaint->reporter_name)->toBe('Anonim');
    expect($complaint->is_anonymous)->toBeTrue();

    $response->assertRedirect(route('complaints.track', ['tiket' => $complaint->ulid]));
});

it('allows a citizen to track a complaint by ticket ulid', function () {
    $category = ComplaintCategory::factory()->create();
    $complaint = Complaint::factory()->create([
        'category_id' => $category->id,
        'title' => 'Saluran Air Mampet',
        'status' => 'unread',
    ]);

    $this->get(route('complaints.track', ['tiket' => $complaint->ulid]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Public/Complaints/Track')
            ->where('tiket', $complaint->ulid)
            ->where('complaint.data.title', 'Saluran Air Mampet')
        );
});

it('allows a citizen to submit a complaint with photo attachments', function () {
    Storage::fake('public');
    $category = ComplaintCategory::factory()->create();

    $file = UploadedFile::fake()->image('bukti_jalan.jpg', 800, 600);

    $payload = [
        'category_id' => $category->id,
        'reporter_name' => 'Ahmad Fauzi',
        'reporter_phone' => '08198765432',
        'title' => 'Jalan Rusak Parah',
        'body' => 'Kondisi jalan berlubang cukup dalam.',
        'location' => 'Depan Balai Desa',
        'is_anonymous' => false,
        'attachments' => [$file],
    ];

    $response = $this->post(route('complaints.store'), $payload);

    $complaint = Complaint::with('attachments')->latest('id')->first();
    expect($complaint)->not->toBeNull();
    expect($complaint->attachments)->toHaveCount(1);
    expect($complaint->attachments->first()->file_path)->not->toBeNull();

    $response->assertRedirect(route('complaints.track', ['tiket' => $complaint->ulid]));
});

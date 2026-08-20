<?php

use App\Enums\PublicationStatus;
use App\Models\VillagePage;

test('anyone can view published village pages index', function () {
    VillagePage::factory()->count(3)->create([
        'status' => PublicationStatus::Published->value,
        'published_at' => now()->subDay(),
    ]);

    VillagePage::factory()->create([
        'status' => PublicationStatus::Draft->value,
    ]);

    $response = $this->get(route('profile.index'));

    $response->assertStatus(200);
    // Drafts shouldn't be included in the Inertia props, we can assert on view if needed.
});

test('anyone can view published village page detail', function () {
    $page = VillagePage::factory()->create([
        'status' => PublicationStatus::Published->value,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get(route('profile.show', $page->slug));

    $response->assertStatus(200);
});

test('draft village page returns 404', function () {
    $page = VillagePage::factory()->create([
        'status' => PublicationStatus::Draft->value,
    ]);

    $response = $this->get(route('profile.show', $page->slug));

    $response->assertStatus(404);
});

<?php

use App\Enums\PublicationStatus;
use App\Models\Umkm;
use App\Models\UmkmCategory;

test('public can view umkm index with Published status only', function () {
    Umkm::factory(3)->create(['status' => PublicationStatus::Published, 'published_at' => now()]);
    Umkm::factory(2)->create(['status' => PublicationStatus::Draft]);

    $this->get(route('umkm.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Frontend/Umkm/Index')
            ->has('umkms.data', 3)
        );
});

test('public can filter and search umkm', function () {
    $category = UmkmCategory::factory()->create(['slug' => 'kuliner']);

    Umkm::factory()->create([
        'title' => 'Sate Madura',
        'category_id' => $category->id,
        'status' => PublicationStatus::Published,
        'published_at' => now(),
    ]);

    Umkm::factory()->create([
        'title' => 'Toko Kelontong',
        'status' => PublicationStatus::Published,
        'published_at' => now(),
    ]);

    // Test Search
    $this->get(route('umkm.index', ['search' => 'Sate']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Frontend/Umkm/Index')
            ->has('umkms.data', 1)
            ->where('umkms.data.0.title', 'Sate Madura')
        );

    // Test Category Filter
    $this->get(route('umkm.index', ['kategori' => 'kuliner']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Frontend/Umkm/Index')
            ->has('umkms.data', 1)
            ->where('umkms.data.0.title', 'Sate Madura')
        );
});

test('public can view umkm detail and view count is incremented', function () {
    $umkm = Umkm::factory()->create([
        'status' => PublicationStatus::Published,
        'published_at' => now(),
        'view_count' => 0,
    ]);

    $this->get(route('umkm.show', $umkm->slug))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Frontend/Umkm/Show')
            ->where('umkm.data.id', $umkm->id)
        );

    expect($umkm->fresh()->view_count)->toBe(1);
});

test('public cannot view unPublished umkm detail', function () {
    $umkm = Umkm::factory()->create([
        'status' => PublicationStatus::Draft,
    ]);

    $this->get(route('umkm.show', $umkm->slug))
        ->assertNotFound();
});

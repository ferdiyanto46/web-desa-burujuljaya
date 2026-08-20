<?php

use App\Enums\PublicationStatus;
use App\Models\VillageService;

test('public can view published village services', function () {
    VillageService::factory()->count(2)->published()->create();
    VillageService::factory()->create(['status' => PublicationStatus::Draft]);

    $this->get(route('village-services.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Public/Services/Index')
            ->has('villageServices.data', 2)
        );
});

test('public can view specific published village service', function () {
    $service = VillageService::factory()->published()->create();

    $this->get(route('village-services.show', $service->slug))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Public/Services/Show')
            ->where('villageService.data.title', $service->title)
        );
});

test('public cannot view draft village service', function () {
    $service = VillageService::factory()->create(['status' => PublicationStatus::Draft]);

    $this->get(route('village-services.show', $service->slug))
        ->assertNotFound();
});

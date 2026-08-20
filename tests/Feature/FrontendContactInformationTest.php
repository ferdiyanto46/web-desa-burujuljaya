<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\ContactInformation;
use Inertia\Testing\AssertableInertia as Assert;

it('shows published contact information on public index', function () {
    ContactInformation::factory()->create([
        'status' => PublicationStatus::Published,
        'published_at' => now()->subDay(),
        'title' => 'Kantor Desa',
    ]);

    ContactInformation::factory()->create([
        'status' => PublicationStatus::Draft,
        'title' => 'Polindes (Draft)',
    ]);

    $this->get(route('contact-information.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/ContactInformation/Index')
            ->has('contactInformations.data', 1)
            ->where('contactInformations.data.0.title', 'Kantor Desa')
        );
});

it('shows contact information details on public show', function () {
    $contact = ContactInformation::factory()->create([
        'status' => PublicationStatus::Published,
        'published_at' => now()->subDay(),
        'title' => 'Kantor Desa',
        'address' => 'Jl. Pahlawan',
    ]);

    $this->get(route('contact-information.show', $contact->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/ContactInformation/Show')
            ->where('contactInformation.data.title', 'Kantor Desa')
            ->where('contactInformation.data.address', 'Jl. Pahlawan')
        );
});

it('returns 404 for draft contact information on public show', function () {
    $contact = ContactInformation::factory()->create([
        'status' => PublicationStatus::Draft,
    ]);

    $this->get(route('contact-information.show', $contact->slug))
        ->assertNotFound();
});

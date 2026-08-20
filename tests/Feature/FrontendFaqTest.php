<?php

declare(strict_types=1);

use App\Models\Faq;

test('public can view published faqs list', function () {
    Faq::factory()->published()->count(3)->create();
    Faq::factory()->draft()->count(2)->create();

    $response = $this->get(route('faq.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Public/Faq/Index')
        ->has('faqs.data', 3)
    );
});

test('public can view single published faq and increments view count', function () {
    $faq = Faq::factory()->published()->create([
        'view_count' => 0,
    ]);

    $response = $this->get(route('faq.show', $faq->slug));

    $response->assertStatus(200);

    $this->assertDatabaseHas('faqs', [
        'id' => $faq->id,
        'view_count' => 1,
    ]);
});

test('public cannot view draft faq', function () {
    $faq = Faq::factory()->draft()->create();

    $response = $this->get(route('faq.show', $faq->slug));

    $response->assertStatus(404);
});

test('can search faqs', function () {
    Faq::factory()->published()->create(['question' => 'How to reset password?']);
    Faq::factory()->published()->create(['question' => 'Where is the village office?']);

    $response = $this->get(route('faq.index', ['search' => 'password']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Public/Faq/Index')
        ->has('faqs.data', 1)
        ->where('faqs.data.0.question', 'How to reset password?')
    );
});

test('can filter faqs by category', function () {
    Faq::factory()->published()->create(['question' => 'General Question', 'category' => 'General']);
    Faq::factory()->published()->create(['question' => 'Service Question', 'category' => 'Service']);

    $response = $this->get(route('faq.index', ['category' => 'Service']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Public/Faq/Index')
        ->has('faqs.data', 1)
        ->where('faqs.data.0.category', 'Service')
    );
});

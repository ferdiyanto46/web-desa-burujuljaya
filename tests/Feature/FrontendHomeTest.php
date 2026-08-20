<?php

use App\Models\HomepageSection;
use App\Models\User;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('loads the homepage successfully', function () {
    $response = get('/');
    $response->assertStatus(200);
});

it('displays enabled homepage sections', function () {
    HomepageSection::factory()->create([
        'section_key' => 'welcome_message',
        'title' => 'Test Welcome Title',
        'is_enabled' => true,
        'user_id' => $this->user->id,
    ]);

    $response = get('/');
    $response->assertStatus(200);
    $response->assertSee('Test Welcome Title');
});

it('does not display disabled homepage sections', function () {
    HomepageSection::factory()->create([
        'section_key' => 'welcome_message',
        'title' => 'Disabled Welcome Title',
        'is_enabled' => false,
        'user_id' => $this->user->id,
    ]);

    $response = get('/');
    $response->assertStatus(200);
    $response->assertDontSee('Disabled Welcome Title');
});

<?php

use App\Models\HomepageSection;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    $this->admin = User::factory()->create();
    $role = Role::findOrCreate('admin-desa');
    $this->admin->assignRole($role);

    $this->user = User::factory()->create();
});

it('allows admin to view homepage sections index', function () {
    actingAs($this->admin);

    $response = get(route('admin.homepage-sections.index'));
    $response->assertStatus(200);
});

it('prevents regular users from viewing homepage sections', function () {
    actingAs($this->user);

    $response = get(route('admin.homepage-sections.index'));
    $response->assertStatus(403);
});

it('allows admin to update homepage section', function () {
    actingAs($this->admin);

    $section = HomepageSection::factory()->create([
        'section_key' => 'hero_slider',
        'title' => 'Old Title',
        'user_id' => $this->admin->id,
    ]);

    $response = put(route('admin.homepage-sections.update', $section->ulid), [
        'title' => 'New Title',
        'is_enabled' => true,
    ]);

    $response->assertRedirect(route('admin.homepage-sections.index'));
    expect($section->fresh()->title)->toBe('New Title');
});

it('allows admin to toggle homepage section status', function () {
    actingAs($this->admin);

    $section = HomepageSection::factory()->create([
        'is_enabled' => true,
        'user_id' => $this->admin->id,
    ]);

    $response = patch(route('admin.homepage-sections.toggle', $section->ulid));
    $response->assertRedirect();

    expect($section->fresh()->is_enabled)->toBeFalse();
});

it('allows admin to reorder homepage sections', function () {
    actingAs($this->admin);

    $section1 = HomepageSection::factory()->create(['sort_order' => 0, 'user_id' => $this->admin->id]);
    $section2 = HomepageSection::factory()->create(['sort_order' => 1, 'user_id' => $this->admin->id]);

    $response = post(route('admin.homepage-sections.reorder'), [
        'order' => [
            $section1->ulid => 1,
            $section2->ulid => 0,
        ],
    ]);

    $response->assertRedirect();

    expect($section1->fresh()->sort_order)->toBe(1);
    expect($section2->fresh()->sort_order)->toBe(0);
});

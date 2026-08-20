<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Faq;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Setup permissions if they don't exist
    $permissions = [
        'faqs.viewAny',
        'faqs.view',
        'faqs.create',
        'faqs.update',
        'faqs.delete',
        'faqs.restore',
        'faqs.forceDelete',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $this->role = Role::firstOrCreate(['name' => 'operator']);
    $this->role->syncPermissions($permissions);

    $this->user = User::factory()->create();
    $this->user->assignRole($this->role);

    $this->actingAs($this->user);
});

test('can view faqs index', function () {
    Faq::factory()->count(3)->create();

    $response = $this->get(route('admin.faqs.index'));

    $response->assertStatus(200);
});

test('can create faq', function () {
    $data = [
        'question' => 'How does this work?',
        'answer' => '<p>Like this.</p>',
        'category' => 'General',
        'sort_order' => 1,
        'is_featured' => true,
        'status' => PublicationStatus::Published->value,
        'published_at' => now()->format('Y-m-d H:i:s'),
        'meta_title' => 'Test Meta',
    ];

    $response = $this->post(route('admin.faqs.store'), $data);

    $response->assertRedirect(route('admin.faqs.index'));
    $this->assertDatabaseHas('faqs', [
        'question' => 'How does this work?',
        'category' => 'General',
    ]);
});

test('can update faq', function () {
    $faq = Faq::factory()->create();

    $data = [
        'question' => 'Updated Question?',
        'answer' => $faq->answer,
        'category' => 'New Category',
        'sort_order' => 2,
        'is_featured' => false,
        'status' => PublicationStatus::Draft->value,
    ];

    $response = $this->put(route('admin.faqs.update', $faq->ulid), $data);

    $response->assertRedirect(route('admin.faqs.index'));
    $this->assertDatabaseHas('faqs', [
        'id' => $faq->id,
        'question' => 'Updated Question?',
        'category' => 'New Category',
    ]);
});

test('can soft delete faq', function () {
    $faq = Faq::factory()->create();

    $response = $this->delete(route('admin.faqs.destroy', $faq->ulid));

    $response->assertRedirect(route('admin.faqs.index'));
    $this->assertSoftDeleted('faqs', [
        'id' => $faq->id,
    ]);
});

test('can restore soft deleted faq', function () {
    $faq = Faq::factory()->create();
    $faq->delete();

    $this->assertSoftDeleted('faqs', ['id' => $faq->id]);

    $response = $this->post(route('admin.faqs.restore', $faq->ulid));

    $response->assertRedirect(route('admin.faqs.index'));
    $this->assertNotSoftDeleted('faqs', [
        'id' => $faq->id,
    ]);
});

test('can force delete faq', function () {
    $faq = Faq::factory()->create();
    $faq->delete();

    $response = $this->delete(route('admin.faqs.force-delete', $faq->ulid));

    $response->assertRedirect(route('admin.faqs.index'));
    $this->assertDatabaseMissing('faqs', [
        'id' => $faq->id,
    ]);
});

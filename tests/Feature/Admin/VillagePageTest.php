<?php

use App\Enums\PublicationStatus;
use App\Enums\VillagePageType;
use App\Models\User;
use App\Models\VillagePage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

    Permission::findOrCreate('village-pages.viewAny');
    Permission::findOrCreate('village-pages.view');
    Permission::findOrCreate('village-pages.create');
    Permission::findOrCreate('village-pages.update');
    Permission::findOrCreate('village-pages.delete');
    Permission::findOrCreate('village-pages.restore');
    Permission::findOrCreate('village-pages.forceDelete');

    $role = Role::findOrCreate('admin-desa');
    $role->givePermissionTo([
        'village-pages.viewAny', 'village-pages.view', 'village-pages.create',
        'village-pages.update', 'village-pages.delete', 'village-pages.restore', 'village-pages.forceDelete',
    ]);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin-desa');
});

test('admin can view village pages index', function () {
    VillagePage::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)->get(route('admin.village-pages.index'));

    $response->assertStatus(200);
});

test('admin can store village page', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('featured.jpg');

    $data = [
        'page_type' => VillagePageType::History->value,
        'title' => 'Sejarah Desa Test',
        'excerpt' => 'Excerpt',
        'content' => 'Content here',
        'status' => PublicationStatus::Published->value,
        'is_pinned' => false,
        'sort_order' => 1,
        'featured_image' => $file,
    ];

    $response = $this->actingAs($this->admin)->post(route('admin.village-pages.store'), $data);

    $response->assertRedirect(route('admin.village-pages.index'));

    $this->assertDatabaseHas('village_pages', [
        'title' => 'Sejarah Desa Test',
        'page_type' => VillagePageType::History->value,
    ]);
});

test('admin can update village page', function () {
    $page = VillagePage::factory()->create();

    $data = [
        'page_type' => VillagePageType::Geography->value,
        'title' => 'Updated Title',
        'excerpt' => 'Updated Excerpt',
        'content' => 'Updated Content',
        'status' => PublicationStatus::Draft->value,
        'is_pinned' => true,
        'sort_order' => 5,
    ];

    $response = $this->actingAs($this->admin)->put(route('admin.village-pages.update', $page), $data);

    $response->assertRedirect(route('admin.village-pages.index'));

    $this->assertDatabaseHas('village_pages', [
        'id' => $page->id,
        'title' => 'Updated Title',
        'page_type' => VillagePageType::Geography->value,
    ]);
});

test('admin can delete village page', function () {
    $page = VillagePage::factory()->create();

    $response = $this->actingAs($this->admin)->delete(route('admin.village-pages.destroy', $page));

    $response->assertRedirect(route('admin.village-pages.index'));
    $this->assertSoftDeleted($page);
});

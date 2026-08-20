<?php

use App\Enums\PublicationStatus;
use App\Models\Umkm;
use App\Models\UmkmCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Basic permissions for the test
    Permission::firstOrCreate(['name' => 'view any umkm']);
    Permission::firstOrCreate(['name' => 'view umkm']);
    Permission::firstOrCreate(['name' => 'create umkm']);
    Permission::firstOrCreate(['name' => 'update umkm']);
    Permission::firstOrCreate(['name' => 'delete umkm']);
    Permission::firstOrCreate(['name' => 'restore umkm']);
    Permission::firstOrCreate(['name' => 'force delete umkm']);

    $role = Role::firstOrCreate(['name' => 'admin-desa']);
    $role->syncPermissions(Permission::all());

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin-desa');

    $this->user = User::factory()->create();

    Storage::fake('public');
});

test('non-admin cannot access umkm index', function () {
    $this->actingAs($this->user)
        ->get(route('admin.umkm.index'))
        ->assertForbidden();
});

test('admin can view umkm index', function () {
    Umkm::factory(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.umkm.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Umkm/Index')
            ->has('umkms.data', 3)
        );
});

test('admin can view umkm create page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.umkm.create'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Umkm/Create')
        );
});

test('admin can create umkm with gallery', function () {
    $category = UmkmCategory::factory()->create();

    $featured = UploadedFile::fake()->image('featured.jpg');
    $image1 = UploadedFile::fake()->image('gallery1.jpg');
    $image2 = UploadedFile::fake()->image('gallery2.jpg');

    $payload = [
        'category_id' => $category->id,
        'title' => 'Warung Nasi',
        'owner_name' => 'Pak Budi',
        'status' => PublicationStatus::Published->value,
        'featured_image' => $featured,
        'gallery' => [$image1, $image2],
        'latitude' => '-6.123456',
        'longitude' => '106.123456',
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.umkm.store'), $payload)
        ->assertRedirect(route('admin.umkm.index'));

    $umkm = Umkm::with(['photos'])->where('title', 'Warung Nasi')->first();

    expect($umkm)->not->toBeNull()
        ->and($umkm->photos)->toHaveCount(2);

    Storage::disk('public')->assertExists($umkm->featured_image_path);
    Storage::disk('public')->assertExists($umkm->photos[0]->file_path);
});

test('admin can view umkm edit page', function () {
    $umkm = Umkm::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.umkm.edit', $umkm))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Umkm/Edit')
            ->has('umkm')
        );
});

test('admin can update umkm and replace image', function () {
    $umkm = Umkm::factory()->create([
        'featured_image_path' => 'old_image.jpg',
    ]);
    Storage::disk('public')->put('old_image.jpg', 'fake-content');

    $newImage = UploadedFile::fake()->image('new_featured.jpg');

    $payload = [
        'category_id' => $umkm->category_id,
        'title' => 'Warung Makan Baru',
        'owner_name' => $umkm->owner_name,
        'status' => PublicationStatus::Published->value,
        'featured_image' => $newImage,
    ];

    $this->actingAs($this->admin)
        ->put(route('admin.umkm.update', $umkm), $payload)
        ->assertRedirect(route('admin.umkm.index'));

    $umkm->refresh();

    expect($umkm->title)->toBe('Warung Makan Baru')
        ->and($umkm->featured_image_path)->not->toBe('old_image.jpg');

    Storage::disk('public')->assertExists($umkm->featured_image_path);
});

test('admin can soft delete umkm', function () {
    $umkm = Umkm::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.umkm.destroy', $umkm))
        ->assertRedirect(route('admin.umkm.index'));

    $this->assertSoftDeleted($umkm);
});

test('admin can force delete umkm', function () {
    $umkm = Umkm::factory()->create();
    $umkm->delete();

    $this->actingAs($this->admin)
        ->delete(route('admin.umkm.force-delete', $umkm))
        ->assertRedirect();

    $this->assertDatabaseMissing('umkms', ['id' => $umkm->id]);
});

test('admin can restore soft deleted umkm', function () {
    $umkm = Umkm::factory()->create();
    $umkm->delete();

    $this->actingAs($this->admin)
        ->post(route('admin.umkm.restore', $umkm))
        ->assertRedirect();

    $this->assertNotSoftDeleted($umkm);
});

<?php

use App\Enums\PublicationStatus;
use App\Models\Tourism;
use App\Models\TourismCategory;
use App\Models\TourismFacility;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Basic permissions for the test
    Permission::firstOrCreate(['name' => 'view any tourism']);
    Permission::firstOrCreate(['name' => 'view tourism']);
    Permission::firstOrCreate(['name' => 'create tourism']);
    Permission::firstOrCreate(['name' => 'update tourism']);
    Permission::firstOrCreate(['name' => 'delete tourism']);
    Permission::firstOrCreate(['name' => 'restore tourism']);
    Permission::firstOrCreate(['name' => 'force delete tourism']);

    $role = Role::firstOrCreate(['name' => 'admin-desa']);
    $role->syncPermissions(Permission::all());

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin-desa');

    Storage::fake('public');
});

test('admin can view tourism index', function () {
    Tourism::factory(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.tourisms.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Tourism/Index')
            ->has('tourisms.data', 3)
        );
});

test('admin can create tourism with auto maps url and gallery', function () {
    $category = TourismCategory::factory()->create();
    $facility1 = TourismFacility::factory()->create();
    $facility2 = TourismFacility::factory()->create();

    $image1 = UploadedFile::fake()->image('gallery1.jpg');
    $image2 = UploadedFile::fake()->image('gallery2.jpg');

    $payload = [
        'category_id' => $category->id,
        'title' => 'Curug Indah',
        'status' => PublicationStatus::Published->value,
        'latitude' => '-6.123456',
        'longitude' => '106.123456',
        'facilities' => [$facility1->id, $facility2->id],
        'gallery' => [$image1, $image2],
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.tourisms.store'), $payload)
        ->assertRedirect(route('admin.tourisms.index'));

    $tourism = Tourism::with(['facilities', 'photos'])->where('title', 'Curug Indah')->first();

    expect($tourism)->not->toBeNull()
        ->and($tourism->google_maps_url)->toBe('https://maps.google.com/?q=-6.123456,106.123456')
        ->and($tourism->facilities)->toHaveCount(2)
        ->and($tourism->photos)->toHaveCount(2);

    Storage::disk('public')->assertExists($tourism->photos[0]->file_path);
});

test('tourism slug must be unique', function () {
    Tourism::factory()->create(['title' => 'Gunung Ciremai', 'slug' => 'gunung-ciremai']);
    $category = TourismCategory::factory()->create();

    $payload = [
        'category_id' => $category->id,
        'title' => 'Gunung Ciremai',
        'status' => PublicationStatus::Draft->value,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.tourisms.store'), $payload)
        ->assertSessionHasErrors('title'); // Validation should fail due to unique title/slug logic
});

test('admin can delete gallery photo', function () {
    $tourism = Tourism::factory()->create();
    $photo = $tourism->photos()->create([
        'file_path' => 'tourism/gallery/test.jpg',
        'order' => 1,
    ]);

    Storage::disk('public')->put('tourism/gallery/test.jpg', 'fake-image');
    Storage::disk('public')->assertExists($photo->file_path);

    $this->actingAs($this->admin)
        ->delete(route('admin.tourisms.photos.destroy', [$tourism, $photo->ulid]))
        ->assertRedirect();

    expect($tourism->photos()->count())->toBe(0);
    Storage::disk('public')->assertMissing('tourism/gallery/test.jpg');
});

test('admin can soft delete tourism', function () {
    $tourism = Tourism::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.tourisms.destroy', $tourism))
        ->assertRedirect(route('admin.tourisms.index'));

    $this->assertSoftDeleted($tourism);
});

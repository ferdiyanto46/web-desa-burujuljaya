<?php

use App\Enums\PublicationStatus;
use App\Models\User;
use App\Models\VillageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'super-admin']);
    $this->user->assignRole($role);
});

test('admin can view village services index', function () {
    VillageService::factory()->count(3)->create();

    $this->actingAs($this->user)
        ->get(route('admin.village-services.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/VillageServices/Index')
            ->has('villageServices.data', 3)
        );
});

test('admin can store village service with featured image and downloadable form', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->user)
        ->post(route('admin.village-services.store'), [
            'title' => 'Test Service',
            'description' => 'Test Description',
            'status' => PublicationStatus::Published->value,
            'featured_image' => UploadedFile::fake()->image('image.jpg'),
            'downloadable_form' => UploadedFile::fake()->create('form.pdf', 100),
            'is_pinned' => true,
            'sort_order' => 1,
        ]);

    $response->assertRedirect(route('admin.village-services.index'));

    $this->assertDatabaseHas('village_services', [
        'title' => 'Test Service',
        'status' => PublicationStatus::Published->value,
        'downloadable_form_name' => 'form.pdf',
    ]);
});

test('admin can update village service', function () {
    $service = VillageService::factory()->create();

    $response = $this->actingAs($this->user)
        ->put(route('admin.village-services.update', $service->ulid), [
            'title' => 'Updated Service',
            'description' => 'Updated Description',
            'status' => PublicationStatus::Published->value,
        ]);

    $response->assertRedirect(route('admin.village-services.index'));

    $this->assertDatabaseHas('village_services', [
        'id' => $service->id,
        'title' => 'Updated Service',
    ]);
});

test('admin can delete village service', function () {
    $service = VillageService::factory()->create();

    $this->actingAs($this->user)
        ->delete(route('admin.village-services.destroy', $service->ulid))
        ->assertRedirect(route('admin.village-services.index'));

    $this->assertSoftDeleted($service);
});

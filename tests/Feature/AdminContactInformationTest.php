<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\ContactInformation;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::firstOrCreate(['name' => 'contact-information.viewAny']);
    Permission::firstOrCreate(['name' => 'contact-information.view']);
    Permission::firstOrCreate(['name' => 'contact-information.create']);
    Permission::firstOrCreate(['name' => 'contact-information.update']);
    Permission::firstOrCreate(['name' => 'contact-information.delete']);
    Permission::firstOrCreate(['name' => 'contact-information.restore']);
    Permission::firstOrCreate(['name' => 'contact-information.forceDelete']);

    $this->admin = User::factory()->create();
    $role = Role::findOrCreate('admin-desa');
    $role->givePermissionTo(Permission::all());
    $this->admin->assignRole($role);
});

it('can list contact information on admin index', function () {
    ContactInformation::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.contact-information.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/ContactInformation/Index')
            ->has('contactInformations.data', 3)
        );
});

it('can search contact information', function () {
    ContactInformation::factory()->create(['title' => 'Kantor Kepala Desa']);
    ContactInformation::factory()->create(['title' => 'Polindes']);

    $this->actingAs($this->admin)
        ->get(route('admin.contact-information.index', ['search' => 'Kepala']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/ContactInformation/Index')
            ->has('contactInformations.data', 1)
            ->where('contactInformations.data.0.title', 'Kantor Kepala Desa')
        );
});

it('can create contact information', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('kantor.jpg');

    $data = [
        'title' => 'Kantor Desa Burujuljaya',
        'address' => 'Jl. Desa Burujuljaya No.1',
        'phone' => '081234567890',
        'email' => 'info@burujuljaya.desa.id',
        'status' => PublicationStatus::Published->value,
        'published_at' => now()->format('Y-m-d H:i:s'),
        'featured_image' => $file,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.contact-information.store'), $data)
        ->assertRedirect(route('admin.contact-information.index'));

    $this->assertDatabaseHas('contact_information', [
        'title' => 'Kantor Desa Burujuljaya',
        'email' => 'info@burujuljaya.desa.id',
        'status' => PublicationStatus::Published->value,
    ]);

    $contact = ContactInformation::first();
    $this->assertNotNull($contact->featured_image_path);
    Storage::disk('public')->assertExists($contact->featured_image_path);
});

it('validates required fields on create', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.contact-information.store'), [])
        ->assertSessionHasErrors(['title', 'status']);
});

it('can update contact information', function () {
    $contact = ContactInformation::factory()->create(['title' => 'Kantor Lama']);

    $data = [
        'title' => 'Kantor Baru',
        'status' => PublicationStatus::Published->value,
    ];

    $this->actingAs($this->admin)
        ->put(route('admin.contact-information.update', $contact->ulid), $data)
        ->assertRedirect(route('admin.contact-information.index'));

    $this->assertDatabaseHas('contact_information', [
        'id' => $contact->id,
        'title' => 'Kantor Baru',
    ]);
});

it('can soft delete contact information', function () {
    $contact = ContactInformation::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.contact-information.destroy', $contact->ulid))
        ->assertRedirect(route('admin.contact-information.index'));

    $this->assertSoftDeleted($contact);
});

it('can restore soft deleted contact information', function () {
    $contact = ContactInformation::factory()->create();
    $contact->delete();

    $this->actingAs($this->admin)
        ->post(route('admin.contact-information.restore', $contact->ulid))
        ->assertRedirect(route('admin.contact-information.index'));

    $this->assertNotSoftDeleted($contact);
});

it('can force delete contact information and its image', function () {
    Storage::fake('public');

    // Using a simple image path creation since we can't fake the MediaService easily here without uploading first
    $file = UploadedFile::fake()->image('test.jpg');
    $path = $file->store('contact-information', 'public');

    $contact = ContactInformation::factory()->create(['featured_image_path' => $path]);
    $contact->delete(); // must be soft deleted first before force delete route works due to withTrashed()

    $this->actingAs($this->admin)
        ->delete(route('admin.contact-information.force-delete', $contact->ulid))
        ->assertRedirect(route('admin.contact-information.index'));

    $this->assertDatabaseMissing('contact_information', ['id' => $contact->id]);
    Storage::disk('public')->assertMissing($path);
});

it('enforces authorization rules', function () {
    $guest = User::factory()->create();
    // guest doesn't have roles

    $contact = ContactInformation::factory()->create();

    $this->actingAs($guest)
        ->get(route('admin.contact-information.index'))
        ->assertForbidden();

    $this->actingAs($guest)
        ->post(route('admin.contact-information.store'), [])
        ->assertForbidden();
});

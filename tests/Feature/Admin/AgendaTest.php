<?php

use App\Enums\PublicationStatus;
use App\Models\Agenda;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    // Setup Super Admin role and permissions
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::firstOrCreate(['name' => 'view any agendas']);
    Permission::firstOrCreate(['name' => 'view agendas']);
    Permission::firstOrCreate(['name' => 'create agendas']);
    Permission::firstOrCreate(['name' => 'update agendas']);
    Permission::firstOrCreate(['name' => 'delete agendas']);
    Permission::firstOrCreate(['name' => 'restore agendas']);
    Permission::firstOrCreate(['name' => 'force delete agendas']);

    $role = Role::firstOrCreate(['name' => 'Super Admin']);
    $role->givePermissionTo(Permission::all());

    $this->admin = User::factory()->create();
    $this->admin->assignRole('Super Admin');

    $this->user = User::factory()->create(); // User without permissions
});

it('can render agenda index page for authorized users', function () {
    Agenda::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.agendas.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Agendas/Index')
            ->has('agendas.data', 3)
        );
});

it('can filter and search agendas', function () {
    Agenda::factory()->create(['title' => 'Village Meeting', 'location' => 'Hall', 'status' => PublicationStatus::Published]);
    Agenda::factory()->create(['title' => 'Sport Event', 'location' => 'Field', 'status' => PublicationStatus::Draft]);

    // Search
    $this->actingAs($this->admin)
        ->get(route('admin.agendas.index', ['search' => 'Meeting']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Agendas/Index')
            ->has('agendas.data', 1)
        );

    // Filter
    $this->actingAs($this->admin)
        ->get(route('admin.agendas.index', ['status' => 'draft']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Agendas/Index')
            ->has('agendas.data', 1)
        );
});

it('denies access to agenda index for unauthorized users', function () {
    $this->actingAs($this->user)
        ->get(route('admin.agendas.index'))
        ->assertForbidden();
});

it('can store a new agenda with an image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('cover.jpg');

    $data = [
        'title' => 'New Awesome Agenda',
        'content' => '<p>Here is some content</p>',
        'location' => 'Village Hall',
        'start_datetime' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'status' => 'draft',
        'featured_image' => $file,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.agendas.store'), $data)
        ->assertRedirect(route('admin.agendas.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('agendas', [
        'title' => 'New Awesome Agenda',
        'status' => 'draft',
    ]);

    $agenda = Agenda::where('title', 'New Awesome Agenda')->first();
    $this->assertNotNull($agenda->featured_image_path);
    Storage::disk('public')->assertExists($agenda->featured_image_path);
});

it('can update an existing agenda and replace the image', function () {
    Storage::fake('public');

    $agenda = Agenda::factory()->create([
        'user_id' => $this->admin->id,
        'featured_image_path' => 'agendas/old.jpg',
    ]);
    Storage::disk('public')->put('agendas/old.jpg', 'content');

    $newFile = UploadedFile::fake()->image('new.jpg');

    $data = [
        'title' => 'Updated Agenda',
        'content' => '<p>Updated content</p>',
        'location' => 'Main Square',
        'start_datetime' => now()->addDays(3)->format('Y-m-d H:i:s'),
        'status' => 'published',
        'published_at' => now()->format('Y-m-d H:i:s'),
        'featured_image' => $newFile,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.agendas.update', $agenda), array_merge($data, ['_method' => 'PUT']))
        ->assertRedirect(route('admin.agendas.index'));

    $this->assertDatabaseHas('agendas', [
        'id' => $agenda->id,
        'title' => 'Updated Agenda',
    ]);

    $agenda->refresh();

    // Old image should be deleted
    Storage::disk('public')->assertMissing('agendas/old.jpg');
    // New image should exist
    $this->assertNotNull($agenda->featured_image_path);
    Storage::disk('public')->assertExists($agenda->featured_image_path);
});

it('soft deletes the agenda but keeps the image', function () {
    Storage::fake('public');

    $agenda = Agenda::factory()->create([
        'featured_image_path' => 'agendas/keep.jpg',
    ]);
    Storage::disk('public')->put('agendas/keep.jpg', 'content');

    $this->actingAs($this->admin)
        ->delete(route('admin.agendas.destroy', $agenda))
        ->assertRedirect(route('admin.agendas.index'));

    $this->assertSoftDeleted($agenda);
    Storage::disk('public')->assertExists('agendas/keep.jpg');
});

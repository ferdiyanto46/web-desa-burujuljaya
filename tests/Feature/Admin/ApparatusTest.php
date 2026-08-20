<?php

declare(strict_types=1);

use App\Models\Apparatus;
use App\Models\ApparatusPosition;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->role = Role::firstOrCreate(['name' => 'admin-desa']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin-desa');

    $this->position = ApparatusPosition::firstOrCreate(
        ['name' => 'Kepala Desa'],
        ['order' => 1]
    );
});

it('prevents unauthenticated users from accessing apparatus index', function () {
    $this->get(route('admin.apparatus.index'))
        ->assertRedirect(route('login'));
});

it('allows authorized users to view apparatus index', function () {
    Apparatus::factory()->create([
        'position_id' => $this->position->id,
        'name' => 'Budi Santoso',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.apparatus.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Apparatus/Index')
            ->has('apparatuses.data', 1)
            ->has('positions')
        );
});

it('allows authorized users to view create form', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.apparatus.create'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Apparatus/Create')
            ->has('positions')
        );
});

it('can store a new apparatus with photo', function () {
    Storage::fake('public');

    $photo = UploadedFile::fake()->image('kepala_desa.png');

    $response = $this->actingAs($this->admin)
        ->post(route('admin.apparatus.store'), [
            'name' => 'Dr. H. Ahmad Fauzi',
            'position_id' => $this->position->id,
            'nip' => '19750812 200003 1 002',
            'education' => 'S2 Kebijakan Publik',
            'photo' => $photo,
            'is_active' => true,
        ]);

    $response->assertRedirect(route('admin.apparatus.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('apparatus', [
        'name' => 'Dr. H. Ahmad Fauzi',
        'position_id' => $this->position->id,
        'nip' => '19750812 200003 1 002',
    ]);

    $apparatus = Apparatus::where('name', 'Dr. H. Ahmad Fauzi')->first();
    expect($apparatus->photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($apparatus->photo_path);
});

it('allows authorized users to view edit form', function () {
    $apparatus = Apparatus::factory()->create([
        'position_id' => $this->position->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.apparatus.edit', $apparatus))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Apparatus/Edit')
            ->has('apparatus')
            ->has('positions')
        );
});

it('can update an existing apparatus', function () {
    $apparatus = Apparatus::factory()->create([
        'position_id' => $this->position->id,
        'name' => 'Nama Lama',
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('admin.apparatus.update', $apparatus), [
            'name' => 'Nama Baru',
            'position_id' => $this->position->id,
            'nip' => '1234567890',
            'education' => 'S1 Teknik Informatika',
            'is_active' => false,
        ]);

    $response->assertRedirect(route('admin.apparatus.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('apparatus', [
        'id' => $apparatus->id,
        'name' => 'Nama Baru',
        'is_active' => false,
    ]);
});

it('can delete an apparatus', function () {
    $apparatus = Apparatus::factory()->create([
        'position_id' => $this->position->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('admin.apparatus.destroy', $apparatus));

    $response->assertRedirect(route('admin.apparatus.index'))
        ->assertSessionHas('success');

    $this->assertSoftDeleted('apparatus', [
        'id' => $apparatus->id,
    ]);
});

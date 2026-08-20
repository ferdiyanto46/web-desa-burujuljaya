<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $this->superAdmin = User::factory()->create([
        'name' => 'Super Admin',
        'email' => 'superadmin_test@desa.id',
        'password' => Hash::make('Password!123'),
        'is_active' => true,
    ]);
    $this->superAdmin->assignRole('super-admin');

    $this->adminDesa = User::factory()->create([
        'name' => 'Admin Desa',
        'email' => 'admindesa_test@desa.id',
        'password' => Hash::make('Password!123'),
        'is_active' => true,
    ]);
    $this->adminDesa->assignRole('admin-desa');
});

it('allows super-admin to access everything including users and settings', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('admin.dashboard'))
        ->assertOk();

    $this->actingAs($this->superAdmin)
        ->get(route('admin.users.index'))
        ->assertOk();

    $this->actingAs($this->superAdmin)
        ->get(route('admin.settings.index'))
        ->assertOk();

    $this->actingAs($this->superAdmin)
        ->get(route('admin.articles.index'))
        ->assertOk();
});

it('allows admin-desa to access all standard village features', function () {
    $this->actingAs($this->adminDesa)
        ->get(route('admin.dashboard'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.articles.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.announcements.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.agendas.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.galleries.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.documents.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.complaints.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.village-pages.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.village-services.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.contact-information.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.faqs.index'))
        ->assertOk();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.homepage-sections.index'))
        ->assertOk();
});

it('forbids admin-desa from accessing user management', function () {
    $targetUser = User::factory()->create();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.users.index'))
        ->assertForbidden();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.users.create'))
        ->assertForbidden();

    $this->actingAs($this->adminDesa)
        ->post(route('admin.users.store'), [
            'name' => 'Baru',
            'email' => 'baru@example.com',
            'password' => 'Password!123',
            'password_confirmation' => 'Password!123',
            'role' => 'operator',
        ])
        ->assertForbidden();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.users.edit', $targetUser))
        ->assertForbidden();

    $this->actingAs($this->adminDesa)
        ->put(route('admin.users.update', $targetUser), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'operator',
        ])
        ->assertForbidden();

    $this->actingAs($this->adminDesa)
        ->delete(route('admin.users.destroy', $targetUser))
        ->assertForbidden();
});

it('forbids admin-desa from accessing system settings and maintenance', function () {
    $this->actingAs($this->adminDesa)
        ->get(route('admin.settings.index'))
        ->assertForbidden();

    $this->actingAs($this->adminDesa)
        ->put(route('admin.settings.update'), [
            'settings' => ['general.site_name' => 'Hacked Name'],
        ])
        ->assertForbidden();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.backups.index'))
        ->assertForbidden();

    $this->actingAs($this->adminDesa)
        ->get(route('admin.cache.index'))
        ->assertForbidden();
});

<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $role = Role::create(['name' => 'Super Admin']);
    Permission::firstOrCreate(['name' => 'manage settings']);
    $role->givePermissionTo('manage settings');
    $this->admin->assignRole($role);
});

it('can render cache management page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.cache.index'))
        ->assertStatus(200);
});

it('can clear application cache', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.cache.clear'), ['type' => 'application'])
        ->assertRedirect()
        ->assertSessionHas('success');
});

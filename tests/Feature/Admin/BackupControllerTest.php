<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $role = Role::create(['name' => 'Super Admin']);
    Permission::firstOrCreate(['name' => 'manage backups']);
    $role->givePermissionTo('manage backups');
    $this->admin->assignRole($role);
});

it('can render backup index page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.backups.index'))
        ->assertStatus(200);
});

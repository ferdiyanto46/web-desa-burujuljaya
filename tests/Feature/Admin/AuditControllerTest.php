<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $role = Role::create(['name' => 'admin-desa']);
    $this->admin->assignRole($role);
});

it('can render audit dashboard page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.audit.index'))
        ->assertStatus(200);
});

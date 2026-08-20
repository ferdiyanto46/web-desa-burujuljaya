<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\VillageProfileSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(VillageProfileSeeder::class);

    $this->admin = User::factory()->create([
        'name' => 'Admin Desa',
        'email' => 'admin_profile_test@desa.id',
        'password' => Hash::make('Password!123'),
        'is_active' => true,
    ]);
    $this->admin->assignRole('admin-desa');
});

it('allows admin to view village profile edit page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.village-profile.edit'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/VillageProfile/Edit')
            ->has('profile')
            ->has('logoUrl')
        );
});

it('handles corrupted cache gracefully without crashing with incomplete class', function () {
    // Put fake corrupted object into cache
    Cache::forever('village_profile.active', 'corrupted_string');

    $this->actingAs($this->admin)
        ->get(route('admin.village-profile.edit'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/VillageProfile/Edit')
            ->has('profile')
        );
});

it('allows admin to update village profile', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->admin)
        ->put(route('admin.village-profile.update'), [
            'village_name' => 'Desa Burujuljaya Updated',
            'district' => 'Parungponteng',
            'regency' => 'Tasikmalaya',
            'province' => 'Jawa Barat',
            'address' => 'Jl. Raya Burujuljaya No. 10',
            'phone' => '081234567890',
            'email' => 'desa.burujuljaya@example.com',
            'website_url' => 'https://burujuljaya.desa.id',
            'vision' => 'Terwujudnya Desa Burujuljaya yang Mandiri dan Sejahtera',
            'history' => 'Sejarah singkat desa burujuljaya.',
            'area_hectares' => '150.50',
            'latitude' => '-7.4567890',
            'longitude' => '108.1234567',
            'established_year' => 1980,
            'logo' => UploadedFile::fake()->image('logo.png'),
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('village_profiles', [
        'village_name' => 'Desa Burujuljaya Updated',
    ]);
});

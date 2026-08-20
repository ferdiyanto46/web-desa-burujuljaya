<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Buat permission
    $permissions = [
        'view any announcements',
        'view announcements',
        'create announcements',
        'update announcements',
        'delete announcements',
        'restore announcements',
        'force delete announcements',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->syncPermissions($permissions);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

it('prevents unauthenticated users from accessing announcements', function () {
    $this->get(route('admin.announcements.index'))
        ->assertRedirect(route('login'));
});

it('prevents users without permission from accessing announcements', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.announcements.index'))
        ->assertForbidden();
});

it('allows authorized users to view the announcements index', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.announcements.index'))
        ->assertOk();
});

it('can store a new announcement with an image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('announcement.jpg');

    $response = $this->actingAs($this->admin)
        ->post(route('admin.announcements.store'), [
            'title' => 'Pengumuman Penting',
            'excerpt' => 'Ini excerpt',
            'content' => '<p>Isi pengumuman</p>',
            'status' => PublicationStatus::Published->value,
            'is_pinned' => true,
            'featured_image' => $file,
        ]);

    $response->assertRedirect(route('admin.announcements.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('announcements', [
        'title' => 'Pengumuman Penting',
        'is_pinned' => true,
    ]);

    $announcement = Announcement::where('title', 'Pengumuman Penting')->first();
    expect($announcement->featured_image_path)->not->toBeNull();

    Storage::disk('public')->assertExists($announcement->featured_image_path);
});

it('can update an existing announcement and replace the image', function () {
    Storage::fake('public');

    $announcement = Announcement::factory()->create([
        'user_id' => $this->admin->id,
        'featured_image_path' => 'announcements/old_image.jpg',
    ]);

    Storage::disk('public')->put('announcements/old_image.jpg', 'content');

    $newFile = UploadedFile::fake()->image('new_image.png');

    $response = $this->actingAs($this->admin)
        ->put(route('admin.announcements.update', $announcement), [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => PublicationStatus::Draft->value,
            'is_pinned' => false,
            'featured_image' => $newFile,
        ]);

    $response->assertRedirect(route('admin.announcements.index'));

    $this->assertDatabaseHas('announcements', [
        'id' => $announcement->id,
        'title' => 'Updated Title',
    ]);

    $announcement->refresh();
    expect($announcement->featured_image_path)->not->toBe('announcements/old_image.jpg');

    Storage::disk('public')->assertMissing('announcements/old_image.jpg');
    Storage::disk('public')->assertExists($announcement->featured_image_path);
});

it('soft deletes the announcement but keeps the image', function () {
    Storage::fake('public');

    $announcement = Announcement::factory()->create([
        'user_id' => $this->admin->id,
        'featured_image_path' => 'announcements/image.jpg',
    ]);

    Storage::disk('public')->put('announcements/image.jpg', 'content');

    $this->actingAs($this->admin)
        ->delete(route('admin.announcements.destroy', $announcement))
        ->assertRedirect(route('admin.announcements.index'));

    $this->assertSoftDeleted('announcements', [
        'id' => $announcement->id,
    ]);

    Storage::disk('public')->assertExists('announcements/image.jpg');
});

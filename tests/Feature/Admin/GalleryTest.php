<?php

namespace Tests\Feature\Admin;

use App\Enums\PublicationStatus;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $permissions = [
            'galleries.viewAny', 'galleries.view', 'galleries.create',
            'galleries.update', 'galleries.delete', 'galleries.restore', 'galleries.forceDelete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $role = Role::findOrCreate('admin-desa');
        $role->syncPermissions($permissions);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin-desa');

        Storage::fake('public');
    }

    public function test_admin_can_view_galleries_index(): void
    {
        Gallery::factory()->count(3)->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.galleries.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_store_new_gallery(): void
    {
        $file = UploadedFile::fake()->image('cover.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.galleries.store'), [
            'title' => 'Test Gallery',
            'description' => 'Test Description',
            'status' => PublicationStatus::Published->value,
            'is_pinned' => true,
            'featured_image' => $file,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $gallery = Gallery::where('title', 'Test Gallery')->first();

        $response->assertRedirect(route('admin.galleries.edit', $gallery->ulid));
        $this->assertDatabaseHas('galleries', [
            'title' => 'Test Gallery',
            'status' => PublicationStatus::Published->value,
            'is_pinned' => true,
        ]);
        $this->assertNotNull($gallery->published_at);
        $this->assertNotNull($gallery->featured_image_path);
    }

    public function test_admin_can_update_gallery(): void
    {
        $gallery = Gallery::factory()->create([
            'user_id' => $this->admin->id,
            'status' => PublicationStatus::Draft->value,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.galleries.update', $gallery->ulid), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => PublicationStatus::Published->value,
            'is_pinned' => false,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('galleries', [
            'id' => $gallery->id,
            'title' => 'Updated Title',
            'status' => PublicationStatus::Published->value,
        ]);
        $this->assertNotNull($gallery->fresh()->published_at);
    }

    public function test_admin_can_soft_delete_gallery(): void
    {
        $gallery = Gallery::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.galleries.destroy', $gallery->ulid));

        $response->assertRedirect(route('admin.galleries.index'));
        $this->assertSoftDeleted($gallery);
    }
}

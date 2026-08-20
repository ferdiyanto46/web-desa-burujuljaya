<?php

namespace Tests\Feature\Admin;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class GalleryPhotoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Gallery $gallery;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $permissions = [
            'galleries.viewAny', 'galleries.view', 'galleries.create',
            'galleries.update', 'galleries.delete', 'galleries.restore', 'galleries.forceDelete',
            'gallery-photos.viewAny', 'gallery-photos.view', 'gallery-photos.create',
            'gallery-photos.update', 'gallery-photos.delete', 'gallery-photos.restore', 'gallery-photos.forceDelete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $role = Role::findOrCreate('admin-desa');
        $role->syncPermissions($permissions);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin-desa');

        $this->gallery = Gallery::factory()->create(['user_id' => $this->admin->id]);

        Storage::fake('public');
    }

    public function test_admin_can_upload_multiple_photos(): void
    {
        $photos = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg'),
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.galleries.photos.store', $this->gallery->ulid), [
                'photos' => $photos,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseCount('gallery_photos', 2);

        $uploadedPhotos = GalleryPhoto::all();
        $this->assertEquals(1, $uploadedPhotos[0]->sort_order);
        $this->assertEquals(2, $uploadedPhotos[1]->sort_order);

        Storage::disk('public')->assertExists($uploadedPhotos[0]->image_path);
        Storage::disk('public')->assertExists($uploadedPhotos[1]->image_path);
    }

    public function test_admin_can_update_photo_metadata(): void
    {
        $photo = GalleryPhoto::factory()->create(['gallery_id' => $this->gallery->id]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.galleries.photos.update', [$this->gallery->ulid, $photo->ulid]), [
                'caption' => 'Updated Caption',
                'alt_text' => 'Updated Alt',
                'photographer' => 'John Doe',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('gallery_photos', [
            'id' => $photo->id,
            'caption' => 'Updated Caption',
            'alt_text' => 'Updated Alt',
            'photographer' => 'John Doe',
        ]);
    }

    public function test_admin_can_reorder_photos(): void
    {
        $photo1 = GalleryPhoto::factory()->create(['gallery_id' => $this->gallery->id, 'sort_order' => 1]);
        $photo2 = GalleryPhoto::factory()->create(['gallery_id' => $this->gallery->id, 'sort_order' => 2]);
        $photo3 = GalleryPhoto::factory()->create(['gallery_id' => $this->gallery->id, 'sort_order' => 3]);

        // New order: 3, 1, 2
        $response = $this->actingAs($this->admin)
            ->put(route('admin.galleries.photos.reorder', $this->gallery->ulid), [
                'photos' => [
                    $photo3->ulid,
                    $photo1->ulid,
                    $photo2->ulid,
                ],
            ]);

        $response->assertRedirect();

        $this->assertEquals(1, $photo3->fresh()->sort_order);
        $this->assertEquals(2, $photo1->fresh()->sort_order);
        $this->assertEquals(3, $photo2->fresh()->sort_order);
    }

    public function test_admin_can_soft_delete_photo_without_deleting_file(): void
    {
        $photo = GalleryPhoto::factory()->create(['gallery_id' => $this->gallery->id]);

        // Mock file existence
        Storage::disk('public')->put($photo->image_path, 'fake content');

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.galleries.photos.destroy', [$this->gallery->ulid, $photo->ulid]));

        $response->assertRedirect();

        $this->assertSoftDeleted($photo);

        // File should still exist for soft deletes
        Storage::disk('public')->assertExists($photo->image_path);
    }
}

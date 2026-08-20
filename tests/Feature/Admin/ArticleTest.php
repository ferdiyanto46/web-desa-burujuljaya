<?php

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    // Setup Super Admin role and permissions
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::firstOrCreate(['name' => 'view any articles']);
    Permission::firstOrCreate(['name' => 'view articles']);
    Permission::firstOrCreate(['name' => 'create articles']);
    Permission::firstOrCreate(['name' => 'update articles']);
    Permission::firstOrCreate(['name' => 'delete articles']);
    Permission::firstOrCreate(['name' => 'restore articles']);
    Permission::firstOrCreate(['name' => 'force delete articles']);

    $role = Role::firstOrCreate(['name' => 'Super Admin']);
    $role->givePermissionTo(Permission::all());

    $this->admin = User::factory()->create();
    $this->admin->assignRole('Super Admin');

    $this->user = User::factory()->create(); // User without permissions

    $this->category = ArticleCategory::factory()->create();
});

it('can render article index page for authorized users', function () {
    Article::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.articles.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Articles/Index')
            ->has('articles.data', 3)
        );
});

it('can filter and search articles', function () {
    Article::factory()->create(['title' => 'Laravel News', 'status' => PublicationStatus::Published]);
    Article::factory()->create(['title' => 'Vue Updates', 'status' => PublicationStatus::Draft]);

    // Search
    $this->actingAs($this->admin)
        ->get(route('admin.articles.index', ['search' => 'Laravel']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Articles/Index')
            ->has('articles.data', 1)
        );

    // Filter
    $this->actingAs($this->admin)
        ->get(route('admin.articles.index', ['status' => 'draft']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Articles/Index')
            ->has('articles.data', 1)
        );
});

it('denies access to article index for unauthorized users', function () {
    $this->actingAs($this->user)
        ->get(route('admin.articles.index'))
        ->assertForbidden();
});

it('can store a new article with an image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('cover.jpg');

    $data = [
        'category_id' => $this->category->id,
        'title' => 'New Awesome Article',
        'content' => '<p>Here is some content</p>',
        'status' => 'draft',
        'featured_image' => $file,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.articles.store'), $data)
        ->assertRedirect(route('admin.articles.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('articles', [
        'title' => 'New Awesome Article',
        'status' => 'draft',
    ]);

    $article = Article::where('title', 'New Awesome Article')->first();
    $this->assertNotNull($article->featured_image_path);
    Storage::disk('public')->assertExists($article->featured_image_path);
});

it('can update an existing article and replace the image', function () {
    Storage::fake('public');

    $article = Article::factory()->create([
        'user_id' => $this->admin->id,
        'featured_image_path' => 'articles/old.jpg',
    ]);
    Storage::disk('public')->put('articles/old.jpg', 'content');

    $newFile = UploadedFile::fake()->image('new.jpg');

    $data = [
        'category_id' => $this->category->id,
        'title' => 'Updated Article',
        'content' => '<p>Updated content</p>',
        'status' => 'published',
        'featured_image' => $newFile,
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.articles.update', $article), array_merge($data, ['_method' => 'PUT']))
        ->assertRedirect(route('admin.articles.index'));

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'title' => 'Updated Article',
    ]);

    $article->refresh();

    // Old image should be deleted
    Storage::disk('public')->assertMissing('articles/old.jpg');
    // New image should exist
    $this->assertNotNull($article->featured_image_path);
    Storage::disk('public')->assertExists($article->featured_image_path);
});

it('soft deletes the article but keeps the image', function () {
    Storage::fake('public');

    $article = Article::factory()->create([
        'featured_image_path' => 'articles/keep.jpg',
    ]);
    Storage::disk('public')->put('articles/keep.jpg', 'content');

    $this->actingAs($this->admin)
        ->delete(route('admin.articles.destroy', $article))
        ->assertRedirect(route('admin.articles.index'));

    $this->assertSoftDeleted($article);
    Storage::disk('public')->assertExists('articles/keep.jpg');
});

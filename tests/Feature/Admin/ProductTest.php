<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    $this->user = User::factory()->create();
    $permissions = [
        'view products',
        'create products',
        'update products',
        'delete products',
        'restore products',
        'force delete products',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $this->user->givePermissionTo($permissions);

    $this->category = ProductCategory::factory()->create();
});

it('can render product index page', function () {
    Product::factory()->count(3)->create(['category_id' => $this->category->id]);

    $response = $this->actingAs($this->user)->get(route('admin.products.index'));

    $response->assertStatus(200);
});

it('can render product create page', function () {
    $response = $this->actingAs($this->user)->get(route('admin.products.create'));

    $response->assertStatus(200);
});

it('can store a new product', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->user)->post(route('admin.products.store'), [
        'title' => 'Test Product',
        'category_id' => $this->category->id,
        'status' => PublicationStatus::Published->value,
        'price' => 50000,
        'featured_image' => UploadedFile::fake()->image('product.jpg'),
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'title' => 'Test Product',
        'price' => 50000,
    ]);
});

it('can store a new product with gallery', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->user)->post(route('admin.products.store'), [
        'title' => 'Test Product with Gallery',
        'category_id' => $this->category->id,
        'status' => PublicationStatus::Published->value,
        'price' => 50000,
        'gallery' => [
            UploadedFile::fake()->image('gallery1.jpg'),
            UploadedFile::fake()->image('gallery2.jpg'),
        ],
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'title' => 'Test Product with Gallery',
    ]);

    $product = Product::where('title', 'Test Product with Gallery')->first();
    expect($product->photos()->count())->toBe(2);
});

it('can render product edit page', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);

    $response = $this->actingAs($this->user)->get(route('admin.products.edit', $product));

    $response->assertStatus(200);
});

it('can update an existing product', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);

    $response = $this->actingAs($this->user)->put(route('admin.products.update', $product), [
        'title' => 'Updated Product',
        'category_id' => $this->category->id,
        'status' => PublicationStatus::Draft->value,
        'price' => 75000,
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'title' => 'Updated Product',
        'price' => 75000,
    ]);
});

it('can soft delete a product', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);

    $response = $this->actingAs($this->user)->delete(route('admin.products.destroy', $product));

    $response->assertRedirect(route('admin.products.index'));
    $this->assertSoftDeleted($product);
});

it('can restore a soft deleted product', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);
    $product->delete();

    $response = $this->actingAs($this->user)->post(route('admin.products.restore', $product));

    $response->assertRedirect();
    $this->assertNotSoftDeleted($product);
});

it('can force delete a product', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);
    $product->delete();

    $response = $this->actingAs($this->user)->delete(route('admin.products.force-delete', $product));

    $response->assertRedirect();
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

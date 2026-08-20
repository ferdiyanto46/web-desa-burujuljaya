<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render public products page', function () {
    Product::factory()->count(3)->create();

    $response = $this->get(route('products.index'));

    $response->assertStatus(200);
});

it('can render public product detail page', function () {
    $product = Product::factory()->create([
        'status' => PublicationStatus::Published->value,
    ]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertStatus(200);
});

it('returns 404 for draft products', function () {
    $product = Product::factory()->create([
        'status' => PublicationStatus::Draft->value,
    ]);

    $response = $this->get(route('products.show', $product->slug));

    $response->assertStatus(404);
});

it('increments view count on show', function () {
    $product = Product::factory()->create([
        'status' => PublicationStatus::Published->value,
        'view_count' => 0,
    ]);

    $this->get(route('products.show', $product->slug));

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'view_count' => 1,
    ]);
});

it('can filter products by search query', function () {
    $category = ProductCategory::factory()->create();
    Product::factory()->create([
        'title' => 'Kopi Arabica',
        'status' => PublicationStatus::Published->value,
        'category_id' => $category->id,
    ]);
    Product::factory()->create([
        'title' => 'Beras Organik',
        'status' => PublicationStatus::Published->value,
        'category_id' => $category->id,
    ]);

    $response = $this->get(route('products.index', ['search' => 'Kopi']));

    $response->assertStatus(200);
    $response->assertSee('Kopi Arabica');
});

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Mews\Purifier\Facades\Purifier;

final class ProductService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?UploadedFile $cover = null): Product
    {
        return $this->db->transaction(function () use ($data, $cover) {
            $product = clone new Product;

            $data['description'] = Purifier::clean($data['description'] ?? '');

            if ($cover) {
                $path = $cover->store('products', 'public');
                if (is_string($path)) {
                    $data['cover_image'] = $path;
                }
            }

            $product->fill($data);
            $product->save();

            return $product;
        });
    }

    /**
     * Update an existing product.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data, ?UploadedFile $cover = null): Product
    {
        return $this->db->transaction(function () use ($product, $data, $cover) {
            if (isset($data['description']) && is_string($data['description'])) {
                $data['description'] = Purifier::clean($data['description']);
            }

            if ($cover) {
                $path = $cover->store('products', 'public');
                if (is_string($path)) {
                    $data['cover_image'] = $path;
                }
            }

            $product->fill($data);
            $product->save();

            return $product;
        });
    }

    /**
     * Remove (soft delete) a product.
     */
    public function remove(Product $product): void
    {
        $product->delete();
    }
}

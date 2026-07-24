<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

final class ProductObserver
{
    /**
     * Handle the Product "forceDeleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }
    }
}

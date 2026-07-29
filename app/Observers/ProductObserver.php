<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;

final class ProductObserver
{
    public bool $afterCommit = true;

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function created(Product $product): void
    {
        $this->activityLogService->log(
            event: 'created',
            description: "Menambahkan produk UMKM: {$product->name}",
            subject: $product,
            logName: 'product',
        );
    }

    public function updated(Product $product): void
    {
        if ($product->wasChanged('cover_image')) {
            $originalCover = $product->getOriginal('cover_image');
            if (is_string($originalCover)) {
                Storage::disk('public')->delete($originalCover);
            }
        }

        $this->activityLogService->log(
            event: 'updated',
            description: "Memperbarui produk UMKM: {$product->name}",
            subject: $product,
            properties: [
                'old' => $product->getOriginal(),
                'attributes' => $product->getChanges(),
            ],
            logName: 'product',
        );
    }

    public function deleted(Product $product): void
    {
        $this->activityLogService->log(
            event: 'deleted',
            description: "Menghapus (soft delete) produk UMKM: {$product->name}",
            subject: $product,
            logName: 'product',
        );
    }

    public function forceDeleted(Product $product): void
    {
        if (is_string($product->cover_image)) {
            Storage::disk('public')->delete($product->cover_image);
        }

        $this->activityLogService->log(
            event: 'force_deleted',
            description: "Menghapus permanen produk UMKM: {$product->name}",
            subject: $product,
            logName: 'product',
        );
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = ProductCategory::all();
        $umkms = Umkm::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        Product::factory(30)->recycle($users)->recycle($categories)->recycle($umkms)->create();
    }
}

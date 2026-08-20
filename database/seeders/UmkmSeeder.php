<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Umkm;
use App\Models\UmkmCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id');
        $categories = UmkmCategory::pluck('id');

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        // We can't easily recycle Collections with multiple models in the factory definition
        // directly like `->recycle($users)`. Wait, we can if we recycle the model instances, not the IDs.
        // Let's get the collections of models instead of IDs.
        $users = User::inRandomOrder()->limit(20)->get();
        $categories = UmkmCategory::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        Umkm::factory(20)
            ->recycle($users)
            ->recycle($categories)
            ->hasPhotos(3)
            ->create();
    }
}

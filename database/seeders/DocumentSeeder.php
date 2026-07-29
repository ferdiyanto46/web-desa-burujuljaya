<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = DocumentCategory::all();
        $users = User::inRandomOrder()->limit(20)->get();

        if ($categories->isEmpty() || $users->isEmpty()) {
            return;
        }

        Document::factory(100)->recycle($categories)->recycle($users)->create();
    }
}

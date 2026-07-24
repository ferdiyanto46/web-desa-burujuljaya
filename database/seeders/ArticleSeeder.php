<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Require categories to exist
        if (ArticleCategory::count() === 0) {
            $this->call(ArticleCategorySeeder::class);
        }

        $categories = ArticleCategory::all();

        // Get the super-admin or create a dummy author
        $author = User::first() ?? User::factory()->create();

        $this->command->info('  Seeding 15 published articles...');
        foreach (range(1, 15) as $i) {
            Article::factory()
                ->published()
                ->create([
                    'user_id' => $author->id,
                    'category_id' => $categories->random()->id,
                ]);
        }

        $this->command->info('  Seeding 3 featured articles...');
        foreach (range(1, 3) as $i) {
            Article::factory()
                ->published()
                ->featured()
                ->create([
                    'user_id' => $author->id,
                    'category_id' => $categories->random()->id,
                ]);
        }

        $this->command->info('  Seeding 5 draft articles...');
        foreach (range(1, 5) as $i) {
            Article::factory()
                ->draft()
                ->create([
                    'user_id' => $author->id,
                    'category_id' => $categories->random()->id,
                ]);
        }

        $this->command->info('  Seeding 2 scheduled articles...');
        foreach (range(1, 2) as $i) {
            Article::factory()
                ->scheduled()
                ->create([
                    'user_id' => $author->id,
                    'category_id' => $categories->random()->id,
                ]);
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

final class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ComplaintCategory::all();

        if ($categories->isEmpty()) {
            return;
        }

        // Limit data production for development memory safety
        Complaint::factory(50)
            ->recycle($categories)
            ->create();
    }
}

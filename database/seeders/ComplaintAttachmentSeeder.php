<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use Illuminate\Database\Seeder;

final class ComplaintAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $complaints = Complaint::inRandomOrder()->limit(30)->get();

        if ($complaints->isEmpty()) {
            return;
        }

        ComplaintAttachment::factory(40)
            ->recycle($complaints)
            ->create();
    }
}

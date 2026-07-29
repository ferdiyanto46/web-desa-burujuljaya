<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\ComplaintHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ComplaintHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $complaints = Complaint::all();
        $users = User::inRandomOrder()->limit(10)->get();

        if ($complaints->isEmpty()) {
            return;
        }

        ComplaintHistory::factory(60)
            ->recycle($complaints)
            ->recycle($users)
            ->create();
    }
}

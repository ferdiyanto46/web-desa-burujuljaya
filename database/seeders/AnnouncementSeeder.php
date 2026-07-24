<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

final class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::first();
        if (! $author) {
            $this->command->warn('No users found. Please run UserSeeder first.');

            return;
        }

        // 3 active and pinned announcements
        Announcement::factory()
            ->count(3)
            ->published()
            ->pinned()
            ->withEndDate()
            ->create(['user_id' => $author->id]);

        // 5 active standard announcements (no end date)
        Announcement::factory()
            ->count(5)
            ->published()
            ->create(['user_id' => $author->id]);

        // 2 expired announcements
        Announcement::factory()
            ->count(2)
            ->published()
            ->expired()
            ->create(['user_id' => $author->id]);

        // 2 drafts
        Announcement::factory()
            ->count(2)
            ->create(['user_id' => $author->id]);

        // 1 scheduled
        Announcement::factory()
            ->count(1)
            ->scheduled()
            ->create(['user_id' => $author->id]);
    }
}

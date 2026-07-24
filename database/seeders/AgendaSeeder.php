<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Database\Seeder;

final class AgendaSeeder extends Seeder
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

        // 5 upcoming agendas
        Agenda::factory()
            ->count(5)
            ->upcoming()
            ->create(['user_id' => $author->id]);

        // 3 past agendas
        Agenda::factory()
            ->count(3)
            ->past()
            ->create(['user_id' => $author->id]);

        // 2 draft agendas
        Agenda::factory()
            ->count(2)
            ->create(['user_id' => $author->id]);
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\ComplaintHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComplaintHistory>
 */
final class ComplaintHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<ComplaintHistory>
     */
    protected $model = ComplaintHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actorType = fake()->randomElement(['system', 'citizen', 'admin']);

        return [
            'complaint_id' => Complaint::factory(),
            'actor_type' => $actorType,
            'user_id' => $actorType === 'admin' ? User::factory() : null,
            'status_from' => fake()->randomElement([null, 'unread', 'processing']),
            'status_to' => fake()->randomElement(['processing', 'resolved', 'rejected']),
            'notes' => fake()->boolean(70) ? fake()->sentence() : null,
        ];
    }
}

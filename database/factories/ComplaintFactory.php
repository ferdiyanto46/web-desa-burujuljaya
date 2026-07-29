<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complaint>
 */
final class ComplaintFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Complaint>
     */
    protected $model = Complaint::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => ComplaintCategory::factory(),
            'reporter_name' => fake()->name(),
            'reporter_email' => fake()->safeEmail(),
            'reporter_phone' => fake()->numerify('+628##########'),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement(['unread', 'processing', 'resolved', 'rejected']),
            'is_anonymous' => fake()->boolean(30),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

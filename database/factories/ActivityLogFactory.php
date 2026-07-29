<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ActivityLog>
 */
final class ActivityLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<ActivityLog>
     */
    protected $model = ActivityLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'batch_uuid' => fake()->boolean(20) ? (string) Str::uuid() : null,
            'log_name' => fake()->randomElement(['default', 'security', 'system', 'content']),
            'event' => fake()->randomElement(['created', 'updated', 'deleted', 'login', 'logout']),
            'description' => fake()->sentence(),
            'subject_type' => null, // Polymorphic relationships mocked dynamically in seeder or test if needed
            'subject_id' => null,
            'causer_type' => null,
            'causer_id' => null,
            'properties' => [
                'old' => [],
                'attributes' => ['name' => fake()->word()],
                'extra' => [],
            ],
            'ip_address' => fake()->boolean(80) ? fake()->ipv4() : null,
            'user_agent' => fake()->boolean(80) ? fake()->userAgent() : null,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

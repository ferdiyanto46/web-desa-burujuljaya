<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Apparatus;
use App\Models\ApparatusPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Apparatus>
 */
final class ApparatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Apparatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position_id' => ApparatusPosition::factory(),
            'name' => $this->faker->name(),
            'nip' => $this->faker->optional(0.7)->numerify('##################'),
            'education' => $this->faker->optional(0.9)->randomElement(['SMA', 'D3', 'S1', 'S2']),
            'photo_path' => null, // Placeholder for future UI
            'is_active' => $this->faker->boolean(90),
        ];
    }
}

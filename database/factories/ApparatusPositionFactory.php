<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ApparatusPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApparatusPosition>
 */
final class ApparatusPositionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApparatusPosition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->jobTitle(),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}

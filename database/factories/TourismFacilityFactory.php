<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TourismFacility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TourismFacility>
 */
class TourismFacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $facilities = ['Toilet', 'Mushola', 'Tempat Parkir', 'Gazebo', 'Warung Makan', 'Spot Foto'];

        return [
            'name' => $this->faker->randomElement($facilities).' '.$this->faker->word(),
            'icon' => 'check', // Default lucide icon
        ];
    }
}
